<?php

namespace App\Library;

class KafkaQueue
{
    /**
     * 生产消息,如果失败打印日志,无返回值
     *
     * @param string $topicName topic名称
     * @param string $key       消息key，会根据key进行hash存储到对应的partition
     * @param string $message   发送的消息，string或json
     *
     * @return void|bool
     */
    public function producer($topicName, $key, $message)
    {
        $kafkaParams = array(
            'message.timeout.ms'       => 2000,
            'message.send.max.retries' => 1,
            'request.required.acks'    => -1,
        );

        $conf = new \RdKafka\Conf();
        foreach ($kafkaParams as $k => $v) {
            $conf->set($k, $v);
        }

        // 设置回调函数，失败后打印日志
        $conf->setDrMsgCb(function ($kafka, $message) {
            if ( $message->err ) {
                // 记录错误日志
                logger_error('[ERROR] [KafkaQueue] [producer] kafak producer error:', [
                    'topic_name' => $message->topic_name,
                    'key'        => $message->key,
                    'payload'    => $message->payload,
                ]);
                return false;
            }
        });

        $rk = new \RdKafka\Producer($conf);
        $rk->addBrokers(env('KAFKA_BOOTSTRAP_SERVERS'));
        $topic = $rk->newTopic($topicName);

        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message, $key);

        // 轮训查询消息是否发送完成
        $rk->poll(0);
        while ($rk->getOutQLen() > 0) {
            $rk->poll(10);
        }
        return true;
    }

    /**
     * 消费消息
     *
     * @param object $callbackClass 用来消费的类目
     * @param string $callbackFunc  用来消费的函数
     * @param string $topicName     topic名称
     * @param string $groupId       消费组ID
     * @param string $partitions    可选参数，默认启动所有partitions,如果数据量大，可以传指定partition（逗号分隔）
     *                              分多进程启动后台任务
     *
     * @throws \Exception
     */
    public function consumer($callbackClass, $callbackFunc, $topicName, $groupId, $partitions = null)
    {
        // kafka conf
        $kafkaConf = array(
            'metadata.broker.list' => env('KAFKA_BOOTSTRAP_SERVERS'),
            'group.id'             => $groupId,
        );

        $topicParam = array(
            'auto.offset.reset'       => 'largest', // 默认从最新开始
            'auto.commit.enable'      => 'false',  // 关闭自动提交，为保证一致性，采取手工提交
            'auto.commit.interval.ms' => 1000, // 自动提交时间间隔
            'offset.store.method'     => 'broker', // offset存储到broker
        );

        $topicConf = new \RdKafka\TopicConf();
        foreach ($topicParam as $key => $val) {
            $topicConf->set($key, $val);
        }

        // 初始化kafka
        $conf = new \RdKafka\Conf();
        foreach ($kafkaConf as $key => $val) {
            $conf->set($key, $val);
        }
        $rk = new \RdKafka\Consumer($conf);

        $metaData    = $rk->getMetadata(true, null, 10000);
        $topics      = $metaData->getTopics();
        $topicExists = false;

        // 获取partition个数
        $partitionsCnt = 0;
        while ($topics->valid()) {
            $current = $topics->current();
            if ( $topicName == $topics->current()->getTopic() ) {
                $partitionsCnt = $current->getPartitions()->count();
                $topicExists   = true;
                break;
            }
            $current = $topics->next();
        };

        if ( !$topicExists ) {
            // 记录错误日志
            logger_error('[ERROR] [KAFKA_QUEUE_ERROR] [KAFKA_QUEUE_CONSUMER_ERROR] [TOPIC_ERR] KAFKA消费异常:', [
                'message' => "topic $topicName is not exists",
            ]);
            return false;
        }

        $topic = $rk->newTopic($topicName, $topicConf);
        $queue = $rk->newQueue();

        // 并行处理个数,如果单进程处理慢，可以改成多进程处理
        $runPartitions = !is_null($partitions) ? explode(",", $partitions) : null;
        for ($i = 0; $i < $partitionsCnt; $i++) {
            if ( is_null($runPartitions) || in_array($i, $runPartitions) ) {
                $topic->consumeQueueStart($i, RD_KAFKA_OFFSET_STORED, $queue);
            }
        }

        while (true) {
            // 每次消费,超时时间
            $message = $queue->consume(1000);
            if ( !is_null($message) ) {
                switch ($message->err) {
                    case RD_KAFKA_RESP_ERR_NO_ERROR:
                        // do something ,if ok then store offset, else then exit or warning
                        $status = call_user_func_array(array($callbackClass, $callbackFunc),
                            array($message->key, $message->payload)
                        );
                        if ( $status == true ) {
                            $topic->offsetStore($message->partition, $message->offset);
                        } else {
                            // 记录错误日志
                            logger_error('[ERROR] [KAFKA_QUEUE_ERROR] [KAFKA_QUEUE_CONSUMER_ERROR] [CONSUMER_ERROR] KAFKA消费异常:',
                                [
                                    'topicName'   => $topicName,
                                    'partition'   => $message->partition,
                                    'offset'      => $message->offset,
                                    'message_err' => "处理失败，请及时关注",
                                ]);
                        }
                        break;
                    case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                        // echo "No more messages; will wait for more\n";
                        break;
                    case RD_KAFKA_RESP_ERR__TIMED_OUT:
                        logger_error('[ERROR] [KAFKA_QUEUE_ERROR] [KAFKA_QUEUE_CONSUMER_ERROR] [CONSUMER_ERROR] KAFKA消费异常:',
                            [
                                'topicName'   => $topicName,
                                'message_err' => "Timed out",
                            ]);
                        break;
                    default:
                        // 记录错误日志
                        logger_error('[ERROR] [KAFKA_QUEUE_ERROR] [KAFKA_QUEUE_CONSUMER_ERROR] [CONSUMER_ERROR] KAFKA消费异常:',
                            [
                                'topicName'      => $topicName,
                                'message_err'    => $message->err,
                                'message_errstr' => $message->errstr(),
                            ]);
                        break;
                }
            }
        }
    }

}
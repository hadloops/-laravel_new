<?php



if ( !function_exists('logger_error') ) {
    function logger_error($message, $context = [])
    {
        return app('log')->error($message, $context);
    }


}

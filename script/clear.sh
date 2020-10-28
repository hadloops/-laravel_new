#!/bin/bash

find /home/biu/laravel_new/storage/logs/* -mtime +1 -name "*.log.*" -exec rm -rf {} \;
find /home/biu/laravel_new/storage/logs/*  -mtime +1 -name "*.log*" -exec rm -rf {} \;
find /home/biu/laravel_new/storage/logs/*  -mtime +1 -name "*.log" -exec rm -rf {} \;
find /home/biu/laravel_new/storage/logs/*  -mtime +1 -name "*.log" -exec rm -rf {} \;
cd /home/biu/laravel_new/storage/logs && rm -rf *.log

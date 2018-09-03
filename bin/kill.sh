#!/bin/bash

ps -ef | grep 'swole_server' | grep -v grep | awk '{print $2}' | xargs -r kill -9
ps -ef | grep 'sleep' | grep -v grep | awk '{print $2}' | xargs -r kill -9
ps -ef | grep 'test' | grep -v grep | awk '{print $2}' | xargs -r kill -9

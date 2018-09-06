#!/bin/bash

ps -ef | grep 'sserver' | grep -v grep | awk '{print $2}' | xargs -r kill -9

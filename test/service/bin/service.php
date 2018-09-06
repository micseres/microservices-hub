<?php

$client = new swoole_client(SWOOLE_SOCK_TCP);

if (!$client->connect('10.5.0.111', 9502, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}

while (true) {

    $client->send('{"protocol":"1.0","action":"register","route":"sleep","message":"Register me, I am ready","payload":{"route":"sleep","load":"90","time":"09-07-2018 10:00:00.111111"}}');
    echo $client->recv();
    sleep(30);
}

$client->close();
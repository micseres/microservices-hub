<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__.'/../vendor/autoload.php';

$logger = new Logger('server');
try {
    $logger->pushHandler(new StreamHandler('./logs/service.log', Logger::DEBUG));
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
} catch (Exception $e) {

}

$client = new swoole_client(SWOOLE_SOCK_UDP);

while (true) {
    if (!$client->connect('10.5.0.111', 9503, -1)) {
        var_dump("connect failed. Error: {$client->errCode}\n");
    }

    $logger->info("CREATE CONNECTION", $client->getsockname());

    $request = [
        'protocol' => '1.0',
        'action' => 'task',
        'route' => 'sleep',
        'message' => 'Sleep',
        'payload' => [
            'interval' => rand(0,99)
        ]
    ];

    $logger->info("SENT DATA TO SERVER", $request);

    $client->send(json_encode($request));

    $response = json_decode($client->recv(), true);

    if (null !== $response) {
        $logger->info("RECEIVE DATA FROM SERVER", $response);

    }
    $client->close();
    sleep(rand(1, 45));
}

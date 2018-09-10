<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__.'/../vendor/autoload.php';

$logger = new Logger('client');
try {
    $logger->pushHandler(new StreamHandler('./logs/service.log', Logger::DEBUG));
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
} catch (Exception $e) {

}

$logger2 = new Logger('result');
try {
    $logger2->pushHandler(new StreamHandler('./logs/result.log', Logger::DEBUG));
    $logger2->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
} catch (Exception $e) {

}

$client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC);

$client->on("connect", function(swoole_client $cli) use ($logger) {
    swoole_timer_tick(10, function () use ($cli, $logger) {
        $request = [
            'protocol' => '1.0',
            'action' => 'count',
            'route' => 'fibonacci',
            'message' => 'Count fibonacci please',
            'payload' => [
                'number' => rand(0,99)
            ]
        ];

//        $logger->info("SENT DATA TO SERVER", $request);

        $cli->send(json_encode($request));
    });
});

$client->on("receive", function(swoole_client $cli, $data) use ($logger, $logger2) {
    $response = json_decode($data, true);
    //$logger->info("RECEIVE DATA FROM SERVER", $response);

    if ($response['action'] !== 'accepted' && $response['action'] !== 'error') {
        if (isset($response['payload']['fibonacci'])) {
            $logger2->info("DONE {$response['payload']['fibonacci']}", $response['payload']);
        } else {
            $logger2->error("FAIL", array($response));
        }
    }

    usleep(1000);
});


$client->on("error", function(swoole_client $cli) use ($logger) {
    $logger->info("SOCKET ERROR");
});

$client->on("close", function(swoole_client $cli)  use ($logger) {
    $logger->info("SOCKET CONNECTION CLOSE");
});


$client->connect('10.5.0.111', 9503);

$logger->info("CREATE CONNECTION", $client->getsockname());
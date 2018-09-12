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

/**
 * @param $n
 * @return float
 */
function fibonacci($n)
{
    return round(pow((sqrt(5)+1)/2, $n) / sqrt(5));
}

$client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC);

$client->on("connect", function(swoole_client $cli) use ($logger) {
    $request = [
        'protocol' => '1.0',
        'action' => 'register',
        'route' => 'system',
        'message' => 'Register me, I am ready for play',
        'payload' => [
            'route' => 'fibonacci',
            'load' => rand(0,99)
        ]
    ];

    $cli->send(json_encode($request));
    $logger->info("SENT REGISTER REQUEST TO SERVER", $request);

    swoole_timer_tick(1000, function () use ($cli, $logger) {
        $request = [
            'protocol' => '1.0',
            'action' => 'register',
            'route' => 'system',
            'message' => 'Register me, I am ready for play',
            'payload' => [
                'route' => 'fibonacci',
                'load' => rand(0,99)
            ]
        ];

        $cli->send(json_encode($request));
        $logger->info("SENT REGISTER REQUEST TO SERVER", $request);
    });
});

$client->on("receive", function(swoole_client $cli, $data) use ($logger) {
    $request = json_decode($data, true);

    if ($request['route'] === 'fibonacci') {
        $logger->info("RECEIVE WORK DATA FROM SERVER", $request);

        $response = [
            'protocol' => '1.0',
            'action' => $request['action'],
            'route' => $request['route'],
            'message' => 'That is number',
            'payload' => [
                'fibonacci' => 1,//fibonacci($request['payload']['number']),
                'task_id' => $request['payload']['task_id']
            ]
        ];

        $cli->send(json_encode($response));

        $logger->info("SENT SERVICE WORK DONE TO SERVER", (array)$response);
    } else {
        $logger->info("RECEIVE REGISTER DATA FROM SERVER", $request);
    }
});

$client->on("error", function(swoole_client $cli) use ($logger) {
    $logger->info("SOCKET ERROR");
});

$client->on("close", function(swoole_client $cli)  use ($logger) {
    $logger->info("SOCKET CONNECTION CLOSE");
});


$client->connect('10.5.0.111', 9502);

$logger->info("CREATE CONNECTION", $client->getsockname());
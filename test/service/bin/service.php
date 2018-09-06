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

$client = new swoole_client(SWOOLE_SOCK_UDP, SWOOLE_SOCK_ASYNC);

$client->on("connect", function(swoole_client $cli) use ($logger) {
    $request = [
        'protocol' => '1.0',
        'action' => 'register',
        'route' => 'sleep',
        'message' => 'Register me, I am ready for play',
        'payload' => [
            'load' => rand(0,99),
            'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
        ]
    ];

    $cli->send(json_encode($request));
    $logger->info("SENT DATA TO SERVER", $request);
});

$client->on("receive", function(swoole_client $cli, $data) use ($logger) {
    $response = json_decode($data, true);
    $logger->info("RECEIVE DATA FROM SERVER", $response);

    swoole_timer_tick(30000, function () use ($cli, $logger) {
        $request = [
            'protocol' => '1.0',
            'action' => 'register',
            'route' => 'sleep',
            'message' => 'Register me, I am ready for play',
            'payload' => [
                'load' => rand(0,99),
                'time' => (new \DateTime('now'))->format('Y-m-d H:i:s.u')
            ]
        ];

        $cli->send(json_encode($request));
        $logger->info("SENT DATA TO SERVER", $request);
    });
    sleep(1);
});

$client->on("error", function(swoole_client $cli){
    echo "error\n";
});
$client->on("close", function(swoole_client $cli){
    echo "Connection close\n";
});


$client->connect('10.5.0.111', 9502);

$logger->info("CREATE CONNECTION", $client->getsockname());
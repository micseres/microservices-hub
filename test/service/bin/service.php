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

$client = new swoole_client(SWOOLE_SOCK_TCP);

if (!$client->connect('10.5.0.111', 9502, -1)) {
    exit("connect failed. Error: {$client->errCode}\n");
}

$logger->info("CREATE CONNECTION", $client->getsockname());

while (true) {
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

    $client->send(json_encode($request));
    $logger->info("SENT DATA TO SERVER", $request);

    $response = json_decode($client->recv(), true);

    if (null !== $response) {
        $logger->info("RECEIVE DATA FROM SERVER", $response);

    }

    sleep(30);
}

$client->close();
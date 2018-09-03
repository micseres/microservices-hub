<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 30.08.18
 * Time: 17:39
 */

require __DIR__.'/../vendor/autoload.php';

use Micseres\ServiceHub\Protocol\Router;

$router = new Router();

$server = new swoole_server("0.0.0.0", 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$server->set([
    'worker_num' => 16,
    'task_worker_num' => 16,
    //'daemonize' => true,
    'max_request' => 10000,
    'dispatch_mode' => 2,
    'debug_mode'=> 1,
    'log_file' => '../logs/swoole_http_server.log'
]);

/**
 * Receive {"status": "live", "message" => "Register me in router". "payload": {"route", "sleep", "load": 90}}
 * Response {"status": "done", "message" => "You registered in router"}
 */
$server->on('receive', function($server, $fd, $from_id, $data) use ($router) {
    echo "Client {$fd} connected\n";
    $data = json_decode($data, false);

    if (empty($data) || ($data->status !== "live")) {
        $server->send($fd, json_encode([
            "status" => "error",
            "message" => "Undefined command"
        ]));
    } else {
        $client = $server->connection_info($fd);
        var_dump($client);
        $server->send($fd, json_encode([
            "status" => "done",
            "message" => "You registered in router"
        ]));
    }

    echo "Client {$fd} sent OK\n";
});

$server->start();
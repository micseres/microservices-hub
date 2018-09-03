<?php

/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 29.08.18
 * Time: 19:20
 */


use GepurIt\ServiceDispatcher\Socket\Router;

require __DIR__.'/../vendor/autoload.php';

$router = new Router();

$routeServers = $router->getRouteSevers('sleep');

$mainServer = array_shift($routeServers);

$server = new swoole_server($mainServer['ip'], $mainServer['port'], SWOOLE_BASE, SWOOLE_SOCK_TCP);

foreach ($routeServers as $routeServer) {
    $server->addlistener($routeServer['ip'], $routeServer['port'], SWOOLE_SOCK_TCP);
}


$server->set([
    'worker_num' => 16,
    'debug_mode'=> 1,
]);

function ping()
{

}

$server->on('connect', function ($server, $fd){
    echo "Connect client: #{$fd}.\n";
});

// Register the function for the event of receiving
$server->on('receive', function($server, $fd, $from_id, $data) use ($router) {
    $start = (new DateTime('now'))->format('Y-m-d H:i:s');

    $data = json_decode($data, false);

    $process = new swoole_process(function($process) use ($data) {
        echo "the pid of child process is {$process->pid}\n";
        echo"the file descriptor of pipe is {$process->pipe}\n";

        usleep($data->sleep);

        if ($data->sleep > 10) {
            $message = "Sleeping to long";
        } else if ($data->sleep < 10 && $data->sleep > 5) {
            $message = "Sleeping fine";
        } else if ($data->sleep < 5 && $data->sleep > 2) {
            $message = "Sleeping poor";
        } else {
            $message = "Not time to sleep. Just die";
        }

        $process->write($message);
    }, false, true);

    $process->start();
    $message = $process->read();

    $server->send($fd, json_encode([
            "status" => "done",
            "message" => $message,
            "start_at" => $start,
            "done_at" => (new DateTime('now'))->format('Y-m-d H:i:s')
    ]));
});

// Start the server
$server->start();


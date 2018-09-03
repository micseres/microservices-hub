<?php
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

use GepurIt\ServiceDispatcher\Socket\Router;

$router = new Router();

$server = new swoole_server("0.0.0.0", 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$server->set([
        'worker_num' => 16,
        'task_worker_num' => 16,
        //'daemonize' => true,
        'max_request' => 10000,
        'dispatch_mode' => 2,
        'debug_mode'=> 1,
        'log_file' => '../logs/swoole_http_server.log'
    ]);

// Register the function for the event of receiving
$server->on('receive', function($server, $fd, $from_id, $data) use ($router) {
    echo "Client {$fd} connected\n";
    $data = json_decode($data, false);

    if (empty($data) || ($data->route && !$router->isRouteExists($data->route))) {
        $server->send($fd, json_encode([
                "status" => "error",
                "message" => "Route not defined"
            ]));
    } else {
        $data->start_at = (new DateTime('now'))->format('Y-m-d H:i:s');
        $data->client = new stdClass();
        $data->client->id = $fd;
        $route = $router->getRouteSever($data->route);
        $data->server = $route;
        $task_id = $server->task($data);

        $server->send($fd, json_encode([
                "status" => "received",
                "message" => "Data go to work",
                "task_id" => $task_id,
                "start_at" => $data->start_at
            ]));
    }
});

$server->on('task', function($server, $task_id, $from_id, $data) {
    $clientId = $data->client->id;
    echo "Send client request {$clientId} to micro service\n";
    $process = new swoole_process(function($process) use ($data) {
        echo "the pid of child process is {$process->pid}\n";
        echo"the file descriptor of pipe is {$process->pipe}\n";
        $client = new swoole_client(SWOOLE_SOCK_TCP);
        if (!$client->connect($data->server['ip'], $data->server['port'], -1)) {
            exit("connect failed. Error: {$client->errCode}\n");
        }

        $client->send(json_encode($data->payload));
        $response = json_decode($client->recv());
        $data->response = new stdClass();
        $data->response = $response;
        $client->close();
        $process->write(json_encode($data));
    }, false, true);

    $process->start();
    $data = $process->read();

    $server->finish($data);
    echo "Client {$clientId} work complete\n";
});

$server->on('finish', function($server, $task_id, $data) {
    $data = json_decode($data, false);
    $server->send($data->client->id, json_encode([
            "status" => "done",
            "task_id" => $task_id,
            "payload" => [
                $data->response
            ],
            "start_at" => $data->start_at,
            "done_at" => (new DateTime('now'))->format('Y-m-d H:i:s')
        ]));
    echo "Client {$data->client->id} send data back\n";
});

$server->start();



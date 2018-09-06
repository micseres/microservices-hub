<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 16:55
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require __DIR__.'/../vendor/autoload.php';

$logger = new Logger('server');
try {
    $logger->pushHandler(new StreamHandler('./logs/server.log', Logger::DEBUG));
    $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
} catch (Exception $e) {

}

$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

$configuration = new \Micseres\ServiceHub\Service\Configuration($dotenv);

$client = new Raven_Client($configuration->getParameter('SENTRY'));
$error_handler = new Raven_ErrorHandler($client);
$error_handler->registerExceptionHandler();
$error_handler->registerErrorHandler();
$error_handler->registerShutdownFunction();

$router = new \Micseres\ServiceHub\Protocol\Router();
$router->addRoute(new \Micseres\ServiceHub\Protocol\MicroServers\MicroServerRoute('sleep'));

$app = new \Micseres\ServiceHub\App($configuration, $logger, $router);

$server = new \Micseres\ServiceHub\Server\Server($app);

$server->create("0.0.0.0", 9502, SWOOLE_BASE, SWOOLE_SOCK_UDP);

$clientListener = $server->getSwoole()->addListener("0.0.0.0", 9503, SWOOLE_SOCK_UDP);

$clientListener->set([
    'worker_num' => 2,
    'task_worker_num' => 2,
    //'daemonize' => true,
    'max_request' => 10000,
    'dispatch_mode' => 2,
    'debug_mode'=> 1
]);

$clientListener->on('connect', function (\Swoole\Server $server, int $fd) use ($app) {
    $app->getLogger()->info("CLIENT SOCKET connect {$fd}");
});


$clientListener->on('receive', function (\Swoole\Server $server, int $fd, int $reactorId, string $data) use ($app) {
    $app->getLogger()->info("CLIENT SOCKET receive {$fd} connect to {$reactorId}");
    $server->send($fd, $data);
    $app->getLogger()->info("CLIENT SOCKET send ping back");
});

$server->start();
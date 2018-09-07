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
    $logger->pushHandler(new StreamHandler('./var/logs/server.log', Logger::DEBUG));
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

$server = new \Micseres\ServiceHub\Server\BaseServer($app);

$serverEvents = \Micseres\ServiceHub\Server\BaseServer::SERVER_EVENTS;

$setting = [
    'worker_num' => 1,
    'task_worker_num' => 16,
    //'daemonize' => true,
    'max_request' => 10000,
    'dispatch_mode' => 2,
    'debug_mode'=> 1
];

$server->createBaseServer($serverEvents, $setting);

$serviceListenerSetting = [
//    'ssl_cert_file' => 'ssl.cert',
//    'ssl_key_file' => 'ssl.key',
];

$portEvents = \Micseres\ServiceHub\Server\Ports\PortListenerInterface::DEFAULT_EVENTS;

$serviceListener = new \Micseres\ServiceHub\Server\Ports\ServicesPortListener($app);
$server->addListener($serviceListener, $portEvents, "0.0.0.0", 9502, SWOOLE_SOCK_UDP, $serviceListenerSetting);


$clientListenerSetting = [
//    'ssl_cert_file' => 'ssl.cert',
//    'ssl_key_file' => 'ssl.key',
];

$clientListener = new \Micseres\ServiceHub\Server\Ports\ClientsPortListener($app);
$server->addListener($clientListener, $portEvents, "0.0.0.0", 9503, SWOOLE_SOCK_UDP, $clientListenerSetting);


$server->start();
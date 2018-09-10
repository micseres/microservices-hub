<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 03.09.18
 * Time: 16:55
 */

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RavenHandler;
use Micseres\ServiceHub\Protocol\Router;
use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Server\BaseServer;
use Micseres\ServiceHub\Server\Ports\PortListenerInterface;
use Micseres\ServiceHub\Server\Ports\UDPServicesPortListener;
use Micseres\ServiceHub\Server\Ports\UDPClientsPortListener;

ini_set('memory_limit', '4096M');

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

$configuration = new \Micseres\ServiceHub\Service\Configuration($dotenv);

$logger = new Logger('server');

$client = new Raven_Client($configuration->getParameter('SENTRY'));

try {
//    $logger->pushHandler(new StreamHandler('./var/logs/server.log', Logger::DEBUG));
//    $logger->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
//    $logger->pushHandler(new RavenHandler($client, Logger::ERROR));

} catch (Exception $e) {

}

$router = new Router();

$router->addRoute(new \Micseres\ServiceHub\Protocol\MicroServers\MicroServerRoute('fibonacci'));

$clientRequestQuery = new \Micseres\ServiceHub\Server\Exchange\ClientRequestQuery();
$serviceResponseQuery = new \Micseres\ServiceHub\Server\Exchange\ServiceResponseQuery();

$app = new App($configuration, $logger, $router, $clientRequestQuery, $serviceResponseQuery);

$server = new BaseServer($app);

$serverEvents = BaseServer::SERVER_EVENTS;

$setting = [
    'worker_num' => 1,
    'task_worker_num' => 32,
//    'daemonize' => true,
    'max_request' => 100000,
    'dispatch_mode' => 2,
    'debug_mode'=> 0
];

$server->createBaseServer(
    $serverEvents,
    $configuration->getParameter('BASE_SERVER_ADDR'),
    $configuration->getParameter('BASE_SERVER_PORT'),
    $setting
);

$serviceListenerSetting = [
//    'ssl_cert_file' => 'ssl.cert',
//    'ssl_key_file' => 'ssl.key',
];

$portEvents = PortListenerInterface::UDP_EVENTS;

$serviceListener = new UDPServicesPortListener($app);
$server->addListener(
    $serviceListener,
    $portEvents,
    $configuration->getParameter('SERVICE_SERVER_ADDR'),
    $configuration->getParameter('SERVICE_SERVER_PORT'),
    SWOOLE_SOCK_UDP,
    $serviceListenerSetting);


$clientListenerSetting = [
//    'ssl_cert_file' => 'ssl.cert',
//    'ssl_key_file' => 'ssl.key',
];


$clientListener = new UDPClientsPortListener($app);

$server->addListener(
    $clientListener,
    $portEvents,
    $configuration->getParameter('CLIENT_SERVER_ADDR'),
    $configuration->getParameter('CLIENT_SERVER_PORT'),
    SWOOLE_SOCK_UDP,
    $clientListenerSetting
);

$server->start();
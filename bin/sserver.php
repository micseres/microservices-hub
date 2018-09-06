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
} catch (Exception $e) {

}

$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

$configuration = new \Micseres\ServiceHub\Service\Configuration($dotenv);

$router = new \Micseres\ServiceHub\Protocol\Router();

$app = new \Micseres\ServiceHub\App($configuration, $logger, $router);

$server = new \Micseres\ServiceHub\Server\Server($app);

$server->create("0.0.0.0", 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$server->start();
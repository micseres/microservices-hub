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

$app = new \Micseres\ServiceHub\App($configuration);

$server = new \Micseres\ServiceHub\Server\Server($logger);

$server->create("0.0.0.0", 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP);

$pool = new \Micseres\ServiceHub\Server\Pool($logger);
$pool->create($server, 9503, SWOOLE_UNIX_STREAM, SWOOLE_SOCK_TCP);
$server->addPool($pool);

$server->start();
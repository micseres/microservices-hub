<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 07.09.18
 * Time: 16:27
 */

namespace Micseres\ServiceHub\Server;
use \Swoole\Server as SServer;


interface BaseServerListenerInterface
{
    /**
     * @param SServer $server
     * @return mixed
     */
    public function onStart(SServer $server);

    /**
     * @param SServer $server
     * @return mixed
     */
    public function onShutdown(SServer $server);

    /**
     * @param SServer $server
     * @param int $worker_id
     * @return mixed
     */
    public function onWorkerStart(SServer $server, int $worker_id);

    /**
     * @param SServer $server
     * @param int $worker_id
     * @return mixed
     */
    function onWorkerStop(SServer $server, int $worker_id);

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $from_id
     * @return mixed
     */
    public function onConnect(SServer $server, int $fd, int $from_id);

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactor_id
     * @param string $data
     * @return mixed
     */
    public function onReceive(SServer $server, int $fd, int $reactor_id, string $data);

    /**
     * @param SServer $server
     * @param int $fd
     * @param int $reactorId
     * @return mixed
     */
    public function onClose(SServer $server, int $fd, int $reactorId);

    /**
     * @param SServer $server
     * @param int $task_id
     * @param int $src_worker_id
     * @param $data
     * @return mixed
     */
    public function onTask(SServer $server, int $task_id, int $src_worker_id, $data);

    /**
     * @param SServer $server
     * @param int $task_id
     * @param string $data
     * @return mixed
     */
    public function onFinish(SServer $server, int $task_id, string $data);

    //public function onTimer(SServer $server, int $interval);
    //public function onPacket(SServer $server, string $data, array $client_info)
    //public function onBufferFull(SServer $server, int $fd);
    //public function onBufferEmpty(SServer $server, int $fd);
    //public function onPipeMessage(SServer $server, int $from_worker_id, string $message);
    //public function onWorkerError(SServer $server, int $worker_id, int $worker_pid, int $exit_code, int $signal);
    //public function onManagerStart(SServer $server);
    //public function onManagerStop(SServer $server);

}
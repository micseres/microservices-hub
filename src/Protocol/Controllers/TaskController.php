<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 14:06
 */

namespace Micseres\ServiceHub\Protocol\Controllers;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Requests\ClientRequest;
use Micseres\ServiceHub\Protocol\Requests\ServiceRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;

/**
 * Class TaskController
 * @package Micseres\ServiceHub\Server\Controllers
 */
class TaskController implements ControllerInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * MicroServer constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * @param ClientRequest $request
     * @return Response
     */
    public function taskEnquiry(ClientRequest $request): Response
    {

    }

    /**
     * @param ServiceRequest $request
     * @return Response
     */
    public function taskComplete(ServiceRequest $request): Response
    {

    }
}
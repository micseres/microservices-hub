<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 13:55
 */

namespace Micseres\ServiceHub\Protocol\Controllers;

use Micseres\ServiceHub\App;
use Micseres\ServiceHub\Protocol\Requests\ServiceRequest;
use Micseres\ServiceHub\Protocol\Responses\Response;

/**
 * Class SystemController
 * @package Micseres\ServiceHub\Server\Controllers\Service
 */
class SystemController implements ControllerInterface
{
    const SYSTEM_ROUTE = 'system';

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
     * @param ServiceRequest $request
     * @return Response
     */
    public function register(ServiceRequest $request): Response
    {

    }
}
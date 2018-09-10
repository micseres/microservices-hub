<?php
/**
 * Created by PhpStorm.
 * User: zogxray
 * Date: 10.09.18
 * Time: 14:24
 */

namespace Micseres\ServiceHub\Protocol\Controllers;


use Micseres\ServiceHub\App;

/**
 * Class TimerController
 * @package Micseres\ServiceHub\Protocol\Controllers
 */
class TimerController implements ControllerInterface
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
}
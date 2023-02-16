<?php

namespace app\core;

/**
 * Class Loader
 * @author Snithfferx <jecheverria@bytes4run.com>
 * @package app\core
 * @version 2.0.5 dev r1
 */

use app\core\classes\ControllerClass;
use app\core\libraries\AuthenticationLibrary;
use app\core\helpers\MessengerHelper;
use app\core\helpers\ViewBuilderHelper;
use app\core\helpers\RouterHelper;

require "helpers/DefinerHelper.php";

class LoaderClass
{
    /**
     * @var object Contiene el objeto de la clase controller
     */
    private $controller;
    /**
     * @var bool Contiene la resolución sí el usuario está o no logueado.
     */
    private $userAlive;
    /**
     * @var object Contiene ela resolución sí el objeto de la clase authentication
     */
    private $auth;
    /**
     * @var object Contiene el objeto de la clase viewbuilder
     */
    private $viewBuilder;
    /**
     * @var object Contiene el objeto de la clase messenger
     */
    private $messenger;
    public $route;
    /**
     * constructor
     */
    function __construct()
    {
        $this->controller = new ControllerClass;
        $this->auth = new AuthenticationLibrary;
        $this->viewBuilder = new ViewBuilderHelper;
        $this->messenger = new MessengerHelper;
        $this->route = new RouterHelper;
    }
    /**
     * Función que realiza la verificación de las distintas partes de la request
     * para resolverla
     * @return array
     */
    function verifyRequest(): array
    {
        $request = $this->route->resolve();
        if ($this->userAlive == false) {
            if ($request['app_module'] == "users") {
                $response = $this->controller->getResponse($request);
            } else {
                $msg = $this->messenger->build('error', ['code' => 401]);
                $msg['data'] = $request;
                $response = $this->messenger->messageBuilder('message',[
                    'type' => "error", 
                    $msg]);
            }
        } else {
            $response = $this->controller->getResponse($request);
        }
        return $response;
    }
    function display($values)
    {
        return $this->renderView($values);
    }
    function init(): bool
    {
        $this->userAlive = $this->auth->isSessionStarted();
        return $this->userAlive;
    }
    function terminate(): void
    {
        $this->controller = null;
        $this->userAlive = null;
    }
    private function renderView($values): string
    {
        if (isset($values['view'])) {
            if ($this->viewBuilder->find($values['view'])) {
                $response = $this->viewBuilder->build($values);
            } else {
                $msg = $this->messenger->build('error', ['code' => 404, $values]);
                $msg['data'] = $values;
                $error = $this->messenger->messageBuilder('message',[
                    'type' => "error", 
                    $msg]);
                $response = $this->viewBuilder->buildDefault($error);
            }
        } else {
            $response = (is_string($values)) ? $values : json_encode($values);
            echo $response;
        }
        return $response;
    }
}

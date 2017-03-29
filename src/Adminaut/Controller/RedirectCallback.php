<?php

namespace Adminaut\Controller;

use Zend\Mvc\Application;
use Zend\Mvc\Router\RouteInterface;
use Zend\Mvc\Router\Exception;
use Zend\Http\PhpEnvironment\Response;
use Adminaut\Options\UserOptions;

/**
 * Class RedirectCallback
 * @package Adminaut\Controller
 */
class RedirectCallback
{
    /**
     * @var RouteInterface
     */
    private $router;

    /**
     * @var Application
     */
    private $application;

    /**
     * @var UserOptions
     */
    private $options;

    /**
     * @param Application $application
     * @param RouteInterface $router
     * @param UserOptions $options
     */
    public function __construct(Application $application, RouteInterface $router, UserOptions $options)
    {
        $this->router = $router;
        $this->application = $application;
        $this->options = $options;
    }

    /**
     * @return Response
     */
    public function __invoke()
    {
        $routeMatch = $this->application->getMvcEvent()->getRouteMatch();
        $redirect = $this->getRedirect($routeMatch->getMatchedRouteName(), $this->getRedirectRouteFromRequest());
        $response = $this->application->getResponse();
        $response->getHeaders()->addHeaderLine('Location', $redirect);
        $response->setStatusCode(302);
        return $response;
    }

    /**
     * @return bool
     */
    private function getRedirectRouteFromRequest()
    {
        $request  = $this->application->getRequest();
        $redirect = $request->getQuery('redirect');
        if ($redirect && $this->routeExists($redirect)) {
            return $redirect;
        }
        $redirect = $request->getPost('redirect');
        if ($redirect && $this->routeExists($redirect)) {
            return $redirect;
        }
        return false;
    }

    /**
     * @param $route
     * @return bool
     */
    private function routeExists($route)
    {
        try {
            $this->router->assemble([], ['name' => $route]);
        } catch (Exception\RuntimeException $e) {
            return false;
        }
        return true;
    }

    /**
     * @param $currentRoute
     * @param bool|false $redirect
     * @return mixed
     */
    protected function getRedirect($currentRoute, $redirect = false)
    {
        $useRedirect = $this->options->isUseRedirectParameterIfPresent();
        $routeExists = ($redirect && $this->routeExists($redirect));
        if (!$useRedirect || !$routeExists) {
            $redirect = false;
        }
        switch ($currentRoute) {
            case 'adminaut/user/register':
            case 'adminaut/user/login':
            case 'adminaut/user/authenticate':
                $route = ($redirect) ?: $this->options->getLoginRedirectRoute();
                return $this->router->assemble([], ['name' => $route]);
                break;
            case 'adminaut/user/logout':
                $route = ($redirect) ?: $this->options->getLogoutRedirectRoute();
                return $this->router->assemble([], ['name' => $route]);
                break;
            default:
                return $this->router->assemble([], ['name' => 'user']);
        }
    }
}
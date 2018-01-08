<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/** @var \Silex\Application $app */

$yamlLoader = new  \Config\YamlLoader();

$routesFile = '../config/routes.yml';
$routes = $yamlLoader->load($routesFile);

$diFile = '../config/di.yml';
$diConfig = $yamlLoader->load($diFile);

$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
$session = $app['session'];

$menuLoader = new \Model\MenuBuilder($yamlLoader, '../config/menu.yml', $request->getBaseUrl());

/** @var \Twig_Environment $twig */
$twig = $app['twig'];
$defaultDiConfig = [
    'twig' => $twig,
    'request' => $request,
    'session' => $session,
    'menuBuilder' => $menuLoader
];
$di = new \Config\Di($defaultDiConfig, $diConfig);

foreach ($routes as $route) {
    $method = $route['method'];
    $controllerName = $route['controller'];
    $args = [];
    foreach ($route['dependencies'] as $dependency) {
        $args[] = $di->getInstance($dependency);
    }
    $reflection = new \ReflectionClass($controllerName);
    /** @var \Controller\BaseController $controller */
    $controller = $reflection->newInstanceArgs($args);
    if (!$controller instanceof \Controller\BaseController) {
        throw  new \Exception(get_class($controller) . "is not an instance of " . \Controller\BaseController::class);
    }
    //web controller
    $app->$method(
        '/' . $route['bind'],
        function () use ($session, $controller, $request, $route, $twig) {
            if ($controller instanceof \Controller\AuthInterface) {
                if (!$session->get('user')) {
                    $url = $request->getBaseUrl() . '/login';
                    return new RedirectResponse($url);
                }
            }
            $result = $controller->execute();
            return $result;
        }
    )->bind($route['bind']);
}

/*$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});*/

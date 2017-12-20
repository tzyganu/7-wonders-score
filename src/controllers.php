<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

//$ip = $_SERVER['REMOTE_ADDR'];
//if ($ip != '127.0.0.1') {
//    echo "Maintainance";exit;
//}

//Request::setTrustedProxies(array('127.0.0.1'));
$factoryCache = [];
function getFactory($class, $factoryCache) {
    if (!isset($factoryCache[$class])) {
        $factoryCache[$class] = new $class();
    }
    return $factoryCache[$class];
}

/** @var \Silex\Application $app */

$yamlLoader = new  \Config\YamlLoader();

$routesFile = '../config/routes.yml';
$routes = $yamlLoader->load($routesFile);

$diFile = '../config/di.yml';
$diConfig = $yamlLoader->load($diFile);

$request = Request::createFromGlobals();

/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
$session = $app['session'];

/** @var \Twig_Environment $twig */
$twig = $app['twig'];
$defaultDiConfig = [
    'twig' => $twig,
    'request' => $request,
    'session' => $session
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
    if (isset($route['web'])) {
        $app->$method(
            '/' . $route['bind'],
            function () use ($session, $controller, $request, $route, $twig) {
                if ($controller instanceof \Controller\AuthInterface) {
                    if (!$session->get('user')) {
                        $url = $request->getBaseUrl() . '/login';
                        return new RedirectResponse($url);
                    }
                }
                $controller->setApiMode(false);
                $result = $controller->execute();
                if ($result instanceof RedirectResponse) {
                    return $result;
                }
                if (isset($route['template'])) {
                    return $twig->render(
                        $route['template'],
                        [
                            'data' => $result,
                            'session' => $session,
                            'body_class' => str_replace('/', '-', $route['bind']),
                            'page_title' => (isset($route['page_title'])) ? $route['page_title'] : ''
                        ]
                    );
                }
                if (isset($route['redirect'])) {
                    $url = $request->getBaseUrl() . $route['redirect'];
                    return new RedirectResponse($url);
                }
                throw new \Exception("Result format not supported for route " . print_r($route, 1));
            }
        )->bind($route['bind']);
    }

    //api controller
    if (isset($route['api'])) {
        $app->$method(
            '/api/' . $route['bind'],
            function () use ($controller) {
                if ($controller instanceof \Controller\AuthInterface) {
                    throw new Exception("Sorry. Auth required but not yet implemented");
                }
                $controller->setApiMode(true);
                try {
                    $result = $controller->execute();
                    if (is_object($result)) {
                        $methods = ['asArray', 'toArray'];
                        foreach ($methods as $arrayMethod) {
                            if (method_exists($result, $arrayMethod)) {
                                $result = $result->$arrayMethod();
                                break;
                            }
                        }
                    }
                    if (!is_array($result)) {
                        throw new \Exception("Response is not array");
                    }
                    $response = [
                        'success' => true,
                        'data' => $result
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
                return JsonResponse::create($response);
            }
        )->bind('/api/'.$route['bind']);
    }
}

$app->get('/', function () use ($app, $session) {
    return $app['twig']->render('index.html.twig', array('session' => $session));
})
->bind('homepage')
;

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
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
});

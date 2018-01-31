<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/** @var \Silex\Application $app */

$request = Request::createFromGlobals();
/** @var \Symfony\Component\HttpFoundation\Session\Session $session */
$session = $app['session'];
/** @var \Twig_Environment $twig */
$twig = $app['twig'];

$factory = new \Model\Factory([
    \Symfony\Component\HttpFoundation\Request::class => $request,
    \Symfony\Component\HttpFoundation\Session\Session::class => $session,
    \Twig_Environment::class => $twig
]);
/** @var \Config\YamlLoader $yamlLoader */
$yamlLoader = $factory->create(\Config\YamlLoader::class);
$menuLoader = $factory->get(
    \Model\MenuBuilder::class,
    [
        'configFile' => PATH_ROOT.'/config/menu.yml',
        'baseUrl' => $request->getBaseUrl()
    ]
);

$functionsFile = PATH_ROOT.'/config/twig.yml';
$twigFunctions = $yamlLoader->load($functionsFile);
foreach ($twigFunctions as $name => $settings) {
    if (!isset($settings['class'])) {
        continue;
    }
    $class = $settings['class'];
    $data = isset($settings['data']) ? $settings['data'] : [];
    $function = $factory->create($class, $data);
    if (!$function instanceof \Twig\FunctionInterface) {
        continue;
    }
    $twig->addFunction($function->getFunction());
}

$routesFile = PATH_ROOT.'/config/routes.yml';
$routes = $yamlLoader->load($routesFile);

foreach ($routes as $route) {
    $method = $route['method'];
    $app->$method(
        '/' . $route['bind'],
        function () use ($session, $request, $factory, $route) {
            $controllerName = $route['controller'];
            $data = [];
            $vars = ['template', 'selectedMenu', 'pageTitle'];
            foreach ($vars as $var) {
                if (array_key_exists($var, $route)) {
                    $data[$var] = $route[$var];
                }
            }
            $controller = $factory->create($controllerName, $data);
            if (!$controller instanceof \Controller\ControllerInterface) {
                throw  new \Exception(get_class($controller) .
                    " must implement " . \Controller\ControllerInterface::class);
            }
            if ($controller instanceof \Controller\AuthInterface) {
                if (!$session->get('user')) {
                    $url = $request->getBaseUrl() . '/login?back='.base64_encode($route['bind']);
                    return new RedirectResponse($url);
                }
            }
            $result = $controller->execute();
            return $result;
        }
    )->bind($route['bind']);
}

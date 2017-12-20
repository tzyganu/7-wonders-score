<?php
namespace Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;

class RoutingService implements ServiceProviderInterface, BootableProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $app A container instance
     */
    public function register(Container $app)
    {
        $app->register(new ServiceControllerServiceProvider());
        $app['routing.config'] = function ($app) {
            return $app['config']->get('controllers');
        };
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $controllers = $app['routing.config'];
        foreach ($controllers as $controllerName => $controller) {
            $silexName = sprintf('%s.controller', $controllerName);
            $controllerClass = $controller['class'];
            $controllerDependencies = isset($controller['dependencies']) ? $controller['dependencies'] : [];
            $app[$silexName] = function ($app) use ($controllerClass, $controllerDependencies) {
                $reflection = new \ReflectionClass($controllerClass);
                $args = array_map(function ($serviceName) use ($app) {
                    if (substr($serviceName, 0, 7) === 'config.') {
                        $configName = substr($serviceName, 7);
                        return $app['config']->get($configName);
                    }
                    return $app['services'][$serviceName];
                }, $controllerDependencies);
                return $reflection->newInstanceArgs($args);
            };
            foreach ($controller['routes'] as $options) {
                $route = $options['route'];
                if ($route !== '/') {
                    $route = '/service' . $route;
                }
                $action = $options['action'];
                $silexAction = sprintf('%s:%s', $silexName, $action);
                $methods = $options['methods'];
                $app->match($route, $silexAction)->method(implode($methods, '|'));
            }
        }
    }
}

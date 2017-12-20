<?php
namespace Provider;

use Config\Config;
use Config\ConfigLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigService implements ServiceProviderInterface
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
        $app['config.values'] = []; // default initialization values of the config
        $app['config.paths'] = [
            PATH_ROOT . '/app/config',
            //PATH_ROOT . '/app/config/services',
            //PATH_ROOT . '/app/config/routes',
        ];
        //$app['config.cachePath'] = PATH_ROOT . '/app/cache/appUserMatcher.php'; // default cache path

//        $app['config.loader'] = function (Container $app) {
//            return new ConfigLoader($app['config.paths'], $app['config.cachePath']);
//        };
        $app['config'] = function (Container $app) {
            return new Config($app['config.values']);
        };
    }
}

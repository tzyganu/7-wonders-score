<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

define('PATH_ROOT', dirname(__DIR__));
if (file_exists('../generated-conf/config.php')) {
    require_once '../generated-conf/config.php';
}
$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.options'    => array(
        'cache' => false,
    )));
$app->register(new HttpFragmentServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

return $app;

<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

/** @var \Silex\Application $app */
if (file_exists('generated-conf/config.php')) {
    require_once 'generated-conf/config.php';
}
$console = new Application('7 Wonders score', '1.0-dev');
$console->getDefinition()->addOption(
    new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev')
);
$console->setDispatcher($app['dispatcher']);

$factory = new \Model\Factory();
$yamlLoader = $factory->get(\Config\YamlLoader::class);

$commandsFile = 'config/console.yml';
$commands = $yamlLoader->load($commandsFile);

foreach ($commands as $commandClass) {
    $command = $factory->get($commandClass);
    $console->add($command);
}
return $console;

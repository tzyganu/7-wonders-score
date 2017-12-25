<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;


if (file_exists('generated-conf/config.php')) {
    require_once 'generated-conf/config.php';
}
$console = new Application('7 Wonders score', '1.0-dev');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

$yamlLoader = new  \Config\YamlLoader();

$commandsFile = 'config/console.yml';
$commands = $yamlLoader->load($commandsFile);

//TODO: add DI for command classes
foreach ($commands as $commandClass) {
    $command = new $commandClass();
    $console->add($command);
}
return $console;

<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);

$installCommand = new \Console\Command\Install();
$console->add($installCommand);

return $console;

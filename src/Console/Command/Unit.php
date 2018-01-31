<?php
namespace Console\Command;

use Model\Console\CommandRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Unit extends Command
{
    /**
     * @var CommandRunner
     */
    private $commandRunner;

    /**
     * Upgrade constructor.
     * @param CommandRunner $commandRunner
     * @param null $name
     */
    public function __construct(
        CommandRunner $commandRunner,
        $name = null
    ) {
        $this->commandRunner = $commandRunner;
        parent::__construct($name);
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this->setName("test:unit")
            ->setDescription('Run Unit Tests');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandRunner->run('vendor/bin/phpunit', $output, true);
    }
}

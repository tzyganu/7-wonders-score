<?php
namespace Console\Command;

use Model\Console\CommandRunner;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Upgrade extends Command
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
        $this->setName("app:upgrade")
            ->setDescription('Run upgrade scripts');
    }
    /**
     * additional commands to run on install
     * order is important
     * @return array
     */
    private function getCommandsToRun()
    {
        return [
            'vendor/bin/propel model:build', //build propel classes
            'vendor/bin/propel migrate', //update db
        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->commandRunner->run($this->getCommandsToRun(), $output, true);
        $output->writeln("Upgrade complete!");
    }
}

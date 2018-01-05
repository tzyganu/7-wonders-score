<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Upgrade extends Command
{
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
            'vendor/bin/propel migrate', //upadte db
        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getCommandsToRun() as $command) {
            $output->writeln("Executing: ". '  '. $command);
            passthru($command);
        }
        $output->writeln("Upgrade complete!");
    }
}

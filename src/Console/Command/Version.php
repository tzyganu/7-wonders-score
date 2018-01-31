<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Version extends Command
{
    /**
     * configure the command
     */
    protected function configure()
    {
        $this->setName("app:version")
            ->setDescription('Get the app version');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("v".\Model\Version::VERSION);
    }
}

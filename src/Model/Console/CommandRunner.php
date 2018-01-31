<?php
namespace Model\Console;

use Symfony\Component\Console\Output\OutputInterface;

class CommandRunner
{
    /**
     * @param $commands
     * @param OutputInterface $output
     * @param $verbose
     */
    public function run($commands, OutputInterface $output, $verbose)
    {
        if (!is_array($commands)) {
            $commands = [$commands];
        }
        foreach ($commands as $command) {
            if ($verbose) {
                $output->writeln("Executing: ". '  '. $command);
            }
            passthru($command);
        }
    }
}

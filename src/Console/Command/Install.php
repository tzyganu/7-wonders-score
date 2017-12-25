<?php
namespace Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Install extends Command
{
    const PROPEL_FILE = "propel.yml";
    const PROPEL_DIST_FILE = "propel.yml.dist";

    /**
     * configure the command
     */
    protected function configure()
    {
        $this->setName("app:install")
            ->setDescription('Install the application');
    }

    /**
     * @return array
     */
    private function getConfigFilesMap()
    {
        return [
            self::PROPEL_FILE,
            self::PROPEL_DIST_FILE
        ];
    }

    /**
     * @return array
     */
    private function getQuestions()
    {
        return [
            '{dbhost}' => [
                'question' => 'Database hostname: ',
                'default' => "localhost"
            ],
            '{dbport}' => [
                'question' => 'Database Port: ',
                'default' => "3306"
            ],
            '{dbuser}' => [
                'question' => 'Database User: ',
                'default' => "root"
            ],
            '{dbpass}' => [
                'question' => 'Database Password: ',
                'default' => "",
                'hidden' => true
            ],
            '{dbname}' => [
                'question' => 'Database Name: ',
                'default' => "wonders",
            ],
        ];
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
            'vendor/bin/propel config:convert', //build propel config (PHP version)
            'vendor/bin/propel migration:up', //install db
            'bin/console user:create' //create admin user

        ];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists(self::PROPEL_FILE)) {
            $output->writeln("The application is already installed");
            return ;
        }
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $answers = [
            '{ROOT_DIR}' => dirname(dirname(dirname(__DIR__))),
        ];
        foreach ($this->getQuestions() as $key => $question) {
            $q = new Question($question['question']. '(default: '.$question['default'].')', $question['default']);
            if (isset($question['hidden']) && $question['hidden']) {
                $q->setHidden(true);
                $q->setHiddenFallback(true);
            }
            $answers[$key] = $questionHelper->ask($input, $output, $q);
        }
        foreach ($this->getConfigFilesMap() as $file) {
            $source = $file . '.sample';
            $content = file_get_contents($source);
            $content = str_replace(array_keys($answers), array_values($answers), $content);
            file_put_contents($file, $content);
        }
        foreach ($this->getCommandsToRun() as $command) {
            $output->writeln("Executing: ". '  '. $command);
            passthru($command);
        }
        $output->writeln("Installation complete!");
    }
}

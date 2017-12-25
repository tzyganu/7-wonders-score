<?php
namespace Console\Command;

use Model\Hash;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Wonders\User;
use Wonders\UserQuery;

class CreateUser extends Command
{
    /**
     * configure the command
     */
    protected function configure()
    {
        $this->setName("user:create")
            ->setDescription('Create user');
    }

    /**
     * @param $username
     * @return bool
     * @throws \Exception
     */
    private function validateUsername($username)
    {
        if (!$username) {
            throw new \Exception("Username cannot be empty");
        }
        $user = UserQuery::create()->findOneByUsername($username);
        if ($user) {
            throw new \Exception("User with username {$username} already exists");
        }
        return true;
    }

    private function validatePassword($password, $rePassword)
    {
        if (!$password) {
            throw new \Exception("Password cannot be empty");
        }
        if ($password != $rePassword) {
            throw new \Exception("Passwords do not match");
        }
        return true;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $questionHelper */
        $questionHelper = $this->getHelper('question');
        $q = new Question('Pick a username: ', '');
        $username = '';
        while (true) {
            $username = $questionHelper->ask($input, $output, $q);
            try {
                if ($this->validateUsername($username)) {
                    break;
                }
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }

        $qp = new Question('Pick a password: ', '');
        $qp->setHiddenFallback(true);
        $qp->setHidden(true);
        $qrp = new Question('Retype password: ', '');
        $qrp->setHiddenFallback(true);
        $qrp->setHidden(true);
        $password = '';
        while (true) {
            $password = $questionHelper->ask($input, $output, $qp);
            $rePassword = $questionHelper->ask($input, $output, $qrp);
            try {
                if ($this->validatePassword($password, $rePassword)) {
                    break;
                }
            } catch (\Exception $e) {
                $output->writeln($e->getMessage());
            }
        }
        $hashModel = new Hash();
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($hashModel->hash($password));
        $user->save();
        $output->writeln("User {$user->getUsername()} was created");
    }
}

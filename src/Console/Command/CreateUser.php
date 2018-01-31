<?php
namespace Console\Command;

use Model\Console\QuestionFactory;
use Model\Factory\UserFactory;
use Model\Hash;
use Service\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    /**
     * @var \Service\User
     */
    private $userService;
    /**
     * @var QuestionFactory
     */
    private $questionFactory;
    /**
     * @var QuestionHelper
     */
    private $questionHelper;
    /**
     * @var UserFactory
     */
    private $userFactory;
    /**
     * @var Hash
     */
    private $hash;

    /**
     * CreateUser constructor.
     * @param User $userService
     * @param QuestionFactory $questionFactory
     * @param QuestionHelper $questionHelper
     * @param UserFactory $userFactory
     * @param Hash $hash
     * @param null $name
     */
    public function __construct(
        User $userService,
        QuestionFactory $questionFactory,
        QuestionHelper $questionHelper,
        UserFactory $userFactory,
        Hash $hash,
        $name = null
    ) {
        $this->userService      = $userService;
        $this->questionFactory  = $questionFactory;
        $this->questionHelper   = $questionHelper;
        $this->hash             = $hash;
        $this->userFactory      = $userFactory;
        parent::__construct($name);
    }

    /**
     * configure the command
     */
    protected function configure()
    {
        $this->setName("user:create")
            ->setDescription('Create admin user');
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
        $user = $this->userService->getUserByUsername($username);
        if ($user) {
            throw new \Exception("User with username {$username} already exists");
        }
        return true;
    }

    /**
     * @param $password
     * @param $rePassword
     * @return bool
     * @throws \Exception
     */
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
        $questionHelper = $this->questionHelper;
        $q = $this->questionFactory->create([
            'question' => 'Pick a username:'
        ]);
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
        $qp = $this->questionFactory->create([
            'question' => 'Pick a password: '
        ]);
        $qp->setHiddenFallback(true);
        $qp->setHidden(true);
        $qrp = $this->questionFactory->create([
            'question' => 'Retype password: '
        ]);
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
        $user = $this->userFactory->create();
        $user->setUsername($username);
        $user->setPassword($this->hash->hash($password));
        try {
            $this->userService->save($user);
            $output->writeln("User {$user->getUsername()} was created");
        } catch (\Exception $e) {
            $output->writeln("There was a problem creating the user: ".$e->getMessage());
        }
    }
}

<?php
namespace Test\Unit\Console\Command;

use Console\Command\CreateUser;
use Model\Console\QuestionFactory;
use Model\Factory\UserFactory;
use Model\Hash;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Service\User as UserService;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Wonders\User;

class CreateUserTest extends TestCase
{
    /**
     * @var \Service\User | \PHPUnit\Framework\MockObject\MockObject
     */
    private $userService;
    /**
     * @var QuestionFactory | \PHPUnit\Framework\MockObject\MockObject
     */
    private $questionFactory;
    /**
     * @var QuestionHelper | \PHPUnit\Framework\MockObject\MockObject
     */
    private $questionHelper;
    /**
     * @var UserFactory | \PHPUnit\Framework\MockObject\MockObject
     */
    private $userFactory;
    /**
     * @var Hash | \PHPUnit\Framework\MockObject\MockObject
     */
    private $hash;
    /**
     * @var InputInterface | \PHPUnit\Framework\MockObject\MockObject
     */
    private $input;
    /**
     * @var OutputInterface | \PHPUnit\Framework\MockObject\MockObject
     */
    private $output;
    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->userService      = $this->createMock(UserService::class);
        $this->questionFactory  = $this->createMock(QuestionFactory::class);
        $this->questionHelper   = $this->createMock(QuestionHelper::class);
        $this->userFactory      = $this->createMock(UserFactory::class);
        $this->hash             = $this->createMock(Hash::class);
        $this->input            = $this->createMock(InputInterface::class);
        $this->output           = $this->createMock(OutputInterface::class);
    }

    /**
     * cleanup tests
     */
    protected function tearDown()
    {
        $this->userService      = null;
        $this->questionFactory  = null;
        $this->questionHelper   = null;
        $this->userFactory      = null;
        $this->hash             = null;
        $this->input            = null;
        $this->output           = null;
        parent::tearDown();
    }

    /**
     * @tests run command
     */
    public function testRun()
    {
        /** @var MockObject | Question $question */
        $question = $this->createMock(Question::class);
        $this->questionFactory->method('create')->willReturn($question);
        $this->questionHelper->method('ask')->willReturn('dummy');
        $user = $this->createMock(User::class);
        $this->userFactory->method('create')->willReturn($user);
        $this->userService->method('save')->willReturn(1);
        $this->output->expects($this->once())->method('writeln');
        $createUser = new CreateUser(
            $this->userService,
            $this->questionFactory,
            $this->questionHelper,
            $this->userFactory,
            $this->hash
        );
        $createUser->run($this->input, $this->output);
    }

    /**
     * @tests run command with not matching passwords
     */
    public function testRunNotMatchingPasswords()
    {
        /** @var MockObject | Question $question */
        $question = $this->createMock(Question::class);
        $this->questionFactory->method('create')->willReturn($question);
        $this->questionHelper->method('ask')->willReturnOnConsecutiveCalls(
            'username',
            'password1',
            'password11',
            'correct-password',
            'correct-password'
        );
        $this->questionHelper->expects($this->exactly(5))->method('ask');
        $user = $this->createMock(User::class);
        $this->userFactory->method('create')->willReturn($user);
        $this->userService->method('save')->willReturn(1);
        $this->userService->method('getUserByUsername')->willReturn(null);
        $this->output->expects($this->exactly(2))->method('writeln');
        $createUser = new CreateUser(
            $this->userService,
            $this->questionFactory,
            $this->questionHelper,
            $this->userFactory,
            $this->hash
        );
        $createUser->run($this->input, $this->output);
    }

    /**
     * @tests run command with wrong username
     */
    public function testRunWrongUsername()
    {
        /** @var MockObject | Question $question */
        $question = $this->createMock(Question::class);
        $this->questionFactory->method('create')->willReturn($question);
        $this->questionHelper->method('ask')->willReturnOnConsecutiveCalls(
            'username',
            'username',
            'correct-password',
            'correct-password'
        );
        $this->questionHelper->expects($this->exactly(4))->method('ask');
        $user = $this->createMock(User::class);
        $this->userFactory->method('create')->willReturn($user);
        $this->userService->method('save')->willReturn(1);
        $this->userService->method('getUserByUsername')->willReturnOnConsecutiveCalls('someUser', null);
        $this->output->expects($this->exactly(2))->method('writeln');
        $createUser = new CreateUser(
            $this->userService,
            $this->questionFactory,
            $this->questionHelper,
            $this->userFactory,
            $this->hash
        );
        $createUser->run($this->input, $this->output);
    }
}

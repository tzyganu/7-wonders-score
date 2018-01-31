<?php
namespace Test\Unit\Console\Command;

use Console\Command\Install;
use Model\Console\CommandRunner;
use Model\Console\QuestionFactory;
use Model\File\Io;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class InstallTest extends TestCase
{
    /**
     * @var CommandRunner | \PHPUnit\Framework\MockObject\MockObject
     */
    private $commandRunner;
    /**
     * @var QuestionFactory | \PHPUnit\Framework\MockObject\MockObject
     */
    private $questionFactory;
    /**
     * @var QuestionHelper | \PHPUnit\Framework\MockObject\MockObject
     */
    private $questionHelper;
    /**
     * @var Io | \PHPUnit\Framework\MockObject\MockObject
     */
    private $io;
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
        $this->commandRunner    = $this->createMock(CommandRunner::class);
        $this->questionFactory  = $this->createMock(QuestionFactory::class);
        $this->questionHelper   = $this->createMock(QuestionHelper::class);
        $this->io               = $this->createMock(Io::class);
        $this->input            = $this->createMock(InputInterface::class);
        $this->output           = $this->createMock(OutputInterface::class);
        $question               = $this->createMock(Question::class);

        $this->questionFactory->method('create')->willReturn($question);
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->commandRunner    = null;
        $this->questionFactory  = null;
        $this->questionHelper   = null;
        $this->io               = null;
        $this->input            = null;
        $this->output           = null;
        parent::tearDown();
    }

    /**
     * @test Install::run();
     */
    public function testRun()
    {
        $install = new Install(
            $this->commandRunner,
            $this->questionFactory,
            $this->questionHelper,
            $this->io
        );
        $this->io->method('fileExists')->willReturn(false);
        $this->io->method('getContents')->willReturn('dummy content');
        $this->io->method('putContents')->willReturn(true);
        $this->commandRunner->expects($this->once())->method('run');
        $this->questionFactory->expects($this->exactly(5))->method('create');
        $this->output->expects($this->once())->method('writeln');
        $install->run($this->input, $this->output);
    }

    /**
     * @test Install::run() when app is already installed
     */
    public function testRunAlreadyInstalled()
    {
        $install = new Install(
            $this->commandRunner,
            $this->questionFactory,
            $this->questionHelper,
            $this->io
        );
        $this->io->method('fileExists')->willReturn(true);
        $this->io->expects($this->exactly(0))->method('getContents');
        $this->io->expects($this->exactly(0))->method('putContents');
        $this->commandRunner->expects($this->exactly(0))->method('run');
        $this->questionFactory->expects($this->exactly(0))->method('create');
        $this->output->expects($this->once())->method('writeln');
        $install->run($this->input, $this->output);
    }
}

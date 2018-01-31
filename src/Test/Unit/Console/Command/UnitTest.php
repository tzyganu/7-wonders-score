<?php
namespace Test\Unit\Console\Command;

use Console\Command\Unit;
use Console\Command\Upgrade;
use Model\Console\CommandRunner;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnitTest extends TestCase
{
    /**
     * @var CommandRunner | \PHPUnit\Framework\MockObject\MockObject
     */
    private $commandRunner;
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
        $this->commandRunner = $this->createMock(CommandRunner::class);
        $this->input         = $this->createMock(InputInterface::class);
        $this->output        = $this->createMock(OutputInterface::class);
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->commandRunner = null;
        $this->input         = null;
        $this->output        = null;
        parent::tearDown();
    }

    /**
     * @test Install::run();
     */
    public function testRun()
    {
        $install = new Unit(
            $this->commandRunner
        );
        $this->commandRunner->expects($this->once())->method('run');
//        $this->output->expects($this->exactly(0))->method('writeln');
        $install->run($this->input, $this->output);
    }
}

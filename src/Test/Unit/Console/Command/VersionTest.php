<?php
namespace Test\Unit\Console\Command;

use Console\Command\Version;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VersionTest extends TestCase
{
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
        $this->input  = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->input  = null;
        $this->output = null;
        parent::tearDown();
    }

    /**
     * @tests Version::run
     */
    public function testRun()
    {
        $this->output->expects($this->once())->method('writeln');
        $command = new Version();
        $command->run($this->input, $this->output);
    }
}

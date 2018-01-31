<?php
namespace Test\Unit\Controller;

use Controller\Heartbeat;
use Model\ResponseFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class HeartbeatTest extends TestCase
{
    /**
     * @var ResponseFactory | MockObject
     */
    private $responseFactory;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->responseFactory = $this->createMock(ResponseFactory::class);
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->responseFactory = null;
        parent::tearDown();
    }

    /**
     * @tests Heartbeat::execute
     */
    public function testExecute()
    {
        $this->responseFactory->expects($this->once())->method('create')->willReturn('response');
        $controller = new Heartbeat($this->responseFactory);
        $this->assertEquals('response', $controller->execute());
    }
}

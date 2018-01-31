<?php
namespace Test\Unit\Config;

use Config\YamlLoader;
use Model\File\Io;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class YamlLoaderTest extends TestCase
{
    /**
     * @var Io | MockObject
     */
    private $io;

    /**
     * setup tests
     */
    protected function setUp()
    {
        parent::setUp();
        $this->io = $this->createMock(Io::class);
    }

    /**
     * cleanup after tests
     */
    protected function tearDown()
    {
        $this->io = null;
        parent::tearDown();
    }

    /**
     * @tests YamlLoader::load
     */
    public function testLoad()
    {
        $content = "home:\n  controller: Controller\n  method: method\n  bind: bind";
        $this->io->method('getContents')->willReturn($content);

        $loader = new YamlLoader(
            $this->io
        );

        $expected = [
            'home' => [
                'controller' => 'Controller',
                'method' => 'method',
                'bind' => 'bind'
            ]
        ];
        $this->assertEquals($expected, $loader->load('dummy'));
    }

    /**
     * @tests YamlLoader::load with not valid content
     */
    public function testLoadWithNoContent()
    {
        $this->io->method('getContents')->willReturn(false);

        $loader = new YamlLoader(
            $this->io
        );

        $expected = [
            'home' => [
                'controller' => 'Controller',
                'method' => 'method',
                'bind' => 'bind'
            ]
        ];
        $this->expectException(\Exception::class);
        $loader->load('dummy');
    }
}

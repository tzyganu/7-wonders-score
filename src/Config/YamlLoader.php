<?php
namespace Config;

use Model\File\Io;
use Symfony\Component\Yaml\Yaml;

class YamlLoader
{
    /**
     * @var Io
     */
    private $io;

    /**
     * YamlLoader constructor.
     * @param Io $io
     */
    public function __construct(
        Io $io
    ) {
        $this->io = $io;
    }

    /**
     * @param $file
     * @return mixed
     * @throws \Exception
     */
    public function load($file)
    {
        $values = Yaml::parse($this->io->getContents($file), true);
        if (null === $values) {
            throw new \Exception(sprintf('Could not load config %s', $file));
        }
        return $values;
    }
}

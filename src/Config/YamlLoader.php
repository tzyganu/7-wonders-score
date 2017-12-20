<?php
namespace Config;

use Symfony\Component\Yaml\Yaml;

class YamlLoader
{
    public function load($file)
    {
        $values = Yaml::parse(file_get_contents($file), true);
        if (null === $values) {
            throw new \Exception(sprintf('Could not load config %s', $file));
        }
        return $values;
    }
}

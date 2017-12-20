<?php
namespace Config;

class Config
{

    /** @var  array */
    protected $config;
    /**
     * @var ConfigLoader
     */
    protected $configLoader;

    public function __construct(array $values = [])
    {
        $this->config = $values;
    }

    /**
     * Gets a config value
     *
     * @param $key
     *
     * @return string
     */
    public function get($key)
    {
        // Find the value
        $keyArr = explode('.', $key);

        $configValue = &$this->config;
        foreach ($keyArr as $keyPath) {
            $configValue = &$configValue[$keyPath];
        }

        return $configValue;
    }
}

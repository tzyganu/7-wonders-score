<?php
namespace Config;

class Di
{
    /**
     * @var array
     */
    private $instances;
    /**
     * @var array
     */
    private $config;

    /**
     * Di constructor.
     * @param array $instances
     * @param array $config
     */
    public function __construct(
        array $instances,
        array $config
    ) {
        $this->instances = $instances;
        $this->config = $config;
    }

    public function addInstance($key, $value)
    {
        $this->instances[$key] = $value;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasInstance($key)
    {
        return array_key_exists($key, $this->instances);
    }

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function getInstance($key)
    {
        if (!isset($this->instances[$key])) {
            if (!isset($this->config[$key])) {
                throw new \Exception("Di config key {$key} not set");
            }
            $class = $this->config[$key];
            $this->instances[$key] = new $class();
        }
        return $this->instances[$key];
    }
}

<?php
namespace Model;

use Console\Command\Install;

class Factory
{
    /**
     * @var array
     */
    private $diContainerConfig;

    /**
     * Factory constructor.
     * @param array $diContainerConfig
     */
    public function __construct(
        array $diContainerConfig = []
    ) {
        $this->diContainerConfig = $diContainerConfig;
        $this->diContainerConfig[Factory::class] = $this;
    }

    /**
     * @param $class
     * @param array $args
     * @return mixed
     */
    public function create($class, array $args = [])
    {
        $class = ltrim($class, '\\');
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        $arguments = [];
        if ($constructor) {
            $params = $reflection->getConstructor()->getParameters();
            foreach ($params as $param) {
                $type = (string)$param->getType();
                $name = $param->getName();
                if (isset($args[$name])) {
                    $arguments[] = $args[$name];
                } elseif (class_exists($type)) {
                    if (!isset($this->diContainerConfig[$type])) {
                        $this->diContainerConfig[$type] = $this->get($type);
                    }
                    $arguments[] = $this->diContainerConfig[$type];
                } elseif ($param->isDefaultValueAvailable()) {
                    $arguments[] = $param->getDefaultValue();
                }
            }
        }
        if (count($arguments) === 0) {
            return new $class();
        }
        return $reflection->newInstanceArgs($arguments);
    }

    /**
     * @param $class
     * @param array $args
     * @return mixed
     */
    public function get($class, array $args = [])
    {
        if (!isset($this->diContainerConfig[$class])) {
            $this->diContainerConfig[$class] = $this->create($class, $args);
        }
        return $this->diContainerConfig[$class];
    }
}

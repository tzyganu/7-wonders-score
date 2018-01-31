<?php
namespace Model\Filter;

use Model\Filter;

class Factory
{
    const PLAYER_FILTER     = 'player_id';
    const WONDER_FILTER     = 'wonder_id';
    const SIDE_FILTER       = 'side';
    const CATEGORY_FILTER   = 'category_id';
    const NUMBER_FILTER     = 'player_count';
    /**
     * @var \Model\Factory
     */
    private $factory;
    /**
     * @var array
     */
    private $values = [];

    /**
     * Factory constructor.
     * @param \Model\Factory $factory
     */
    public function __construct(
        \Model\Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $key
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function create($key, $data = [])
    {
        $config = $this->getConfig();
        if (!isset($config[$key])) {
            throw new \Exception("Unsupported filter type {$key}");
        }
        $configData = $config[$key];
        $configData = array_merge($this->getDefaults(), $configData, $data);
        $values = $this->getValues($configData['valuesClass']);
        unset($configData['valuesClass']);
        $configData['values'] = $values;
        return $this->factory->create(Filter::class, $configData);
    }

    /**
     * @return array
     */
    private function getDefaults()
    {
        return [
            'useSelectAll' => true,
            'multiple' => true,
            'canGroup' => true,
        ];
    }

    /**
     * @param $class
     * @return mixed
     * @throws \Exception
     */
    private function getValues($class)
    {
        if (!isset($this->values[$class])) {
            $instance = $this->factory->create($class);
            if (!$instance instanceof Provider\ProviderInterface) {
                throw new \Exception($class .' must implement '. Filter\Provider\ProviderInterface::class);
            }
            $this->values[$class] = $instance->getValues();
        }
        return $this->values[$class];
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        return [
            self::PLAYER_FILTER => [
                'name' => 'player_id',
                'label' => 'Player',
                'valuesClass' => Filter\Provider\Player::class
            ],
            self::WONDER_FILTER => [
                'name' => 'wonder_id',
                'label' => 'Wonder',
                'valuesClass' => Filter\Provider\Wonder::class
            ],
            self::SIDE_FILTER => [
                'name' => 'side',
                'label' => 'Side',
                'valuesClass' => Filter\Provider\Side::class
            ],
            self::CATEGORY_FILTER => [
                'name' => 'category_id',
                'label' => 'Score Category',
                'valuesClass' => Filter\Provider\Category::class
            ],
            self::NUMBER_FILTER => [
                'name' => 'player_count',
                'label' => '# of Players',
                'valuesClass' => Filter\Provider\PlayerCount::class
            ],
        ];
    }
}

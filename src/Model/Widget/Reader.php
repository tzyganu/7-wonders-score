<?php
namespace Model\Widget;

use Config\YamlLoader;
use Model\Factory;
use Model\Widget\Provider\ProviderInterface;

class Reader
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * @var GroupFactory
     */
    private $groupFactory;
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $file;

    private $groups;

    /**
     * Reader constructor.
     * @param Factory $factory
     * @param GroupFactory $groupFactory
     * @param YamlLoader $yamlLoader
     * @param string $file
     */
    public function __construct(
        Factory $factory,
        GroupFactory $groupFactory,
        YamlLoader $yamlLoader,
        $file
    ) {
        $this->factory = $factory;
        $this->groupFactory = $groupFactory;
        $this->yamlLoader = $yamlLoader;
        $this->file = $file;
    }

    /**
     * @return Group[]
     */
    public function getWidgetGroups()
    {
        if ($this->groups === null) {
            $this->groups = [];
            $config = $this->yamlLoader->load($this->file);
            foreach ($config as $code => $settings) {
                $providerClass = $settings['provider'];
                $provider = $this->factory->create($providerClass);
                unset($settings['provider']);
                if ($provider instanceof ProviderInterface) {
                    $settings['widgets'] = $provider->getWidgets();
                    $this->groups[] = $this->groupFactory->create($settings);
                }
            }
        }
        return $this->groups;
    }
}

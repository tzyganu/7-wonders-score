<?php
namespace Model\Grid;

use Config\YamlLoader;
use Model\Grid\Column\Factory as ColumnFactory;
use Model\Grid\Button\Factory as ButtonFactory;

class Loader
{
    /**
     * @var Factory
     */
    private $gridFactory;
    /**
     * @var YamlLoader
     */
    private $yamlLoader;
    /**
     * @var string
     */
    private $fileLocation;
    /**
     * @var ColumnFactory
     */
    private $columnFactory;
    /**
     * @var ButtonFactory
     */
    private $buttonFactory;

    /**
     * Loader constructor.
     * @param Factory $gridFactory
     * @param ColumnFactory $columnFactory
     * @param ButtonFactory $buttonFactory
     * @param YamlLoader $yamlLoader
     * @param string $fileLocation
     */
    public function __construct(
        Factory $gridFactory,
        ColumnFactory $columnFactory,
        ButtonFactory $buttonFactory,
        YamlLoader $yamlLoader,
        $fileLocation = '../config/grid/'
    ) {
        $this->gridFactory   = $gridFactory;
        $this->columnFactory = $columnFactory;
        $this->buttonFactory = $buttonFactory;
        $this->yamlLoader    = $yamlLoader;
        $this->fileLocation  = $fileLocation;
    }

    /**
     * @param $name
     * @return \Model\Grid
     */
    public function loadGrid($name)
    {
        $config = $this->yamlLoader->load($this->locateGridConfig($name));
        return $this->buildGrid($config);
    }

    /**
     * @param $name
     * @return string
     */
    private function locateGridConfig($name)
    {
        return $this->fileLocation.$name.'.yml';
    }

    /**
     * @param $config
     * @return \Model\Grid
     */
    private function buildGrid($config)
    {
        $options = isset($config['options']) ? $config['options'] : [];
        $grid = $this->gridFactory->create($options);
        if (isset($config['columns'])) {
            foreach ($config['columns'] as $columnData) {
                $grid->addColumn($this->columnFactory->create($columnData));
            }
        }
        if (isset($config['buttons'])) {
            foreach ($config['buttons'] as $id => $buttonData) {
                $grid->addButton($id, $this->buttonFactory->create($buttonData));
            }
        }
        return $grid;
    }
}

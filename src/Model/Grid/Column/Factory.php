<?php
namespace Model\Grid\Column;

use Model\Grid\Column;

class Factory
{
    /**
     * @var string
     */
    const DEFAULT_TYPE = 'text';
    /**
     * @var array
     */
    private $map = [
        'decimal'    => DecimalColumn::class,
        'icon'       => Icon::class,
        'integer'    => IntegerColumn::class,
        'percentage' => Percentage::class,
        'text'       => Text::class,
        'yesno'      => YesNo::class,
        'link'       => Link::class
    ];
    /**
     * @var \Model\Factory
     */
    private $factory;

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
     * @param $data
     * @return Column //TODO: change to column interface
     * @throws \Exception
     */
    public function create($data)
    {
        $type = isset($data['type']) ? $data['type'] : self::DEFAULT_TYPE;
        unset($data['type']);
        if (!isset($this->map[$type])) {
            throw new \Exception("Unsuported column type {$type}");
        }
        $class = $this->map[$type];
        //TODO: check if class implements interface
        return $this->factory->create($class, ['options' => $data]);
    }
}

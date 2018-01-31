<?php
namespace Model;

class WidgetFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * WidgetFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return Widget
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Widget::class, $data);
    }
}

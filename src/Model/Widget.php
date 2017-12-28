<?php
namespace Model;

class Widget
{
    /**
     * @var string
     */
    protected $value;
    /**
     * @var string
     */
    protected $label;
    /**
     * @var string
     */
    protected $link;
    /**
     * @var string
     */
    protected $icon;
    /**
     * @var string
     */
    protected $class;

    /**
     * Column constructor.
     * @param array $options
     */
    public function __construct(array $options) {
        $fields = $this->getConstructorOptionsFields();
        foreach ($fields as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
    }

    /**
     * @return array
     */
    protected function getConstructorOptionsFields()
    {
        return ['value', 'label', 'link', 'icon', 'class'];
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }
}

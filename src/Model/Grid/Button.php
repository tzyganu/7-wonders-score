<?php
namespace Model\Grid;

class Button
{
    const DEFAULT_CLASS = 'btn btn-primary';
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $class;
    /**
     * @var string
     */
    protected $label;

    /**
     * Button constructor.
     * @param $label
     * @param $url
     * @param null $class
     */
    public function __construct($label, $url, $class = null)
    {
        $this->setLabel($label);
        $this->setUrl($url);
        if ($class !== null) {
            $this->setClass($class);
        } else {
            $this->setClass(self::DEFAULT_CLASS);
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
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
}

<?php
namespace Model;

class Widget
{
    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $link;
    /**
     * @var string
     */
    private $icon;
    /**
     * @var string
     */
    private $cssClass = 'bg-teal';
    /**
     * @var string
     */
    private $outerClass = 'col-lg-6 col-xs-12';
    /**
     * @var string
     */
    private $template;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Widget constructor.
     * @param \Twig_Environment $twig
     * @param array $options
     * @param string $template
     */
    public function __construct(
        \Twig_Environment $twig,
        array $options,
        $template = 'widget.html.twig'
    ) {
        $fields = $this->getConstructorOptionsFields();
        foreach ($fields as $field) {
            if (isset($options[$field])) {
                $method = 'set'.ucfirst($field);
                $this->$method($options[$field]);
            }
        }
        $this->twig     = $twig;
        $this->template = $template;
    }

    /**
     * @return array
     */
    private function getConstructorOptionsFields()
    {
        return ['value', 'label', 'link', 'icon', 'cssClass', 'outerClass'];
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
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param string $cssClass
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * @return string
     */
    public function getOuterClass()
    {
        return $this->outerClass;
    }

    /**
     * @param string $outerClass
     */
    public function setOuterClass($outerClass)
    {
        $this->outerClass = $outerClass;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render(
            $this->template,
            [
                'label' => $this->getLabel(),
                'value' => $this->getValue(),
                'cssClass' => $this->getCssClass(),
                'outerClass' => $this->getOuterClass(),
                'icon' => $this->getIcon(),
                'link' => $this->getLink()
            ]
        );
    }
}

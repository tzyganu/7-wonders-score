<?php
namespace Model\Widget;

use Model\Widget;

class Group
{
    /**
     * @var Widget[]
     */
    private $widgets;
    /**
     * @var string
     */
    private $label;
    /**
     * @var string
     */
    private $template;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Group constructor.
     * @param \Twig_Environment $twig
     * @param $label
     * @param array $widgets
     * @param string $template
     */
    public function __construct(
        \Twig_Environment $twig,
        $label,
        array $widgets = [],
        $template = 'widget/group.html.twig'
    ) {
        $this->twig     = $twig;
        $this->widgets  = $widgets;
        $this->label    = $label;
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render(
            $this->template,
            [
                'widgets' => $this->widgets,
                'label' => $this->label
            ]
        );
    }
}

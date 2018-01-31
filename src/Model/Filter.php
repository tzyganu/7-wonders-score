<?php
namespace Model;

class Filter
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $label;
    /**
     * @var array
     */
    private $values;
    /**
     * @var array
     */
    private $selected;
    /**
     * @var bool
     */
    private $useSelectAll;
    /**
     * @var bool
     */
    private $canGroup;
    /**
     * @var bool
     */
    private $canGroupSelected;
    /**
     * @var string
     */
    private $template;
    /**
     * @var bool
     */
    private $multiple;

    /**
     * Filter constructor.
     * @param \Twig_Environment $twig
     * @param $name
     * @param $label
     * @param $values
     * @param array $selected
     * @param bool $multiple
     * @param bool $useSelectAll
     * @param bool $canGroup
     * @param bool $canGroupSelected
     * @param string $template
     */
    public function __construct(
        \Twig_Environment $twig,
        $name,
        $label,
        $values,
        $selected = [],
        $multiple = true,
        $useSelectAll = true,
        $canGroup = true,
        $canGroupSelected = false,
        $template = 'filter.html.twig'
    ) {
        $this->twig             = $twig;
        $this->name             = $name;
        $this->label            = $label;
        $this->values           = $values;
        $this->multiple         = $multiple;
        $this->selected         = $selected;
        $this->useSelectAll     = $useSelectAll;
        $this->canGroup         = $canGroup;
        $this->canGroupSelected = $canGroupSelected;
        $this->template         = $template;
    }

    /**
     * @return string
     */
    public function render()
    {
        return $this->twig->render(
            $this->template,
            [
                'name' => $this->name,
                'label' => $this->label,
                'values' => $this->values,
                'multiple' => $this->multiple,
                'selected' => $this->selected,
                'useSelectAll' => $this->useSelectAll,
                'canGroup' => $this->canGroup,
                'canGroupSelected' => $this->canGroupSelected,
            ]
        );
    }
}

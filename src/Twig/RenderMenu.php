<?php
namespace Twig;

use Model\MenuBuilder;

class RenderMenu implements FunctionInterface
{
    /**
     * @var MenuBuilder
     */
    private $menuBuilder;

    /**
     * RenderMenu constructor.
     * @param MenuBuilder $menuBuilder
     */
    public function __construct(MenuBuilder $menuBuilder)
    {
        $this->menuBuilder = $menuBuilder;
    }

    /**
     * @return \Twig_Function
     */
    public function getFunction()
    {
        $menuBuilder = $this->menuBuilder;
        return new \Twig_Function('render_menu', function ($selectedMenu = '') use ($menuBuilder) {
            return $menuBuilder->renderMenu($selectedMenu);
        });
    }
}

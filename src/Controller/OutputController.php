<?php
namespace Controller;

use Model\MenuBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class OutputController extends BaseController
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;
    /**
     * @var string
     */
    protected $selectedMenu = '';
    /**
     * @var MenuBuilder
     */
    protected $menuBuilder;

    /**
     * @var string
     */
    protected $template = '';

    /**
     * OutputController constructor.
     * @param Request $request
     * @param Session $session
     * @param MenuBuilder $menuBuilder
     * @param \Twig_Environment $twig
     */
    public function __construct(
        Request $request,
        Session $session,
        MenuBuilder $menuBuilder,
        \Twig_Environment $twig
    ) {
        $this->menuBuilder = $menuBuilder;
        $this->twig = $twig;
        parent::__construct($request, $session);
    }

    /**
     * @param $vars
     * @return string
     */
    public function render($vars)
    {
        if (!$this->template) {
            return '';
        }
        $allVars = $this->getAllVars($vars);
        return $this->twig->render($this->template, $allVars);
    }

    /**
     * @param $vars
     * @return array
     */
    protected function getAllVars($vars)
    {
        $defaults = [
            'session' => $this->session,
            'nav_menu' => $this->menuBuilder->renderMenu($this->selectedMenu),
            'flash_messages' => $this->renderFlashMessages()
        ];
        return array_merge($defaults, $vars);
    }
}

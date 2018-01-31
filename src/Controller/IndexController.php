<?php
namespace Controller;

use Model\Widget;

class IndexController implements ControllerInterface
{
    /**
     * @var string
     */
    private $template;
    /**
     * @var string
     */
    private $selectedMenu;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var Widget\ReaderFactory
     */
    private $widgetReaderFactory;

    /**
     * IndexController constructor.
     * @param \Twig_Environment $twig
     * @param Widget\ReaderFactory $widgetReaderFactory
     * @param string $template
     * @param string $selectedMenu
     */
    public function __construct(
        \Twig_Environment $twig,
        Widget\ReaderFactory $widgetReaderFactory,
        $template = 'index.html.twig',
        $selectedMenu = 'dashboard'
    ) {
        $this->twig                = $twig;
        $this->widgetReaderFactory = $widgetReaderFactory;
        $this->template            = $template;
        $this->selectedMenu        = $selectedMenu;
    }

    /**
     * @return string
     */
    public function execute()
    {
        $reader = $this->widgetReaderFactory->create(['file' => PATH_ROOT.'/config/widget.yml']);
        return $this->twig->render(
            $this->template,
            [
                'widgetGroups' => $reader->getWidgetGroups()
            ]
        );
    }
}

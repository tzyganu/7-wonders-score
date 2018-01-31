<?php
namespace Controller\WonderGroup;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\WonderGroupFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Service\Wonder;
use Service\WonderGroup;
use Symfony\Component\HttpFoundation\Request;

class EditWonderGroup implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var WonderGroup
     */
    private $wonderGroupService;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var WonderGroupFactory
     */
    private $wonderGroupFactory;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var array
     */
    private $selectedMenu;
    /**
     * @var string
     */
    private $template;
    /**
     * @var Wonder
     */
    private $wonderService;

    /**
     * EditWonderGroup constructor.
     * @param Request $request
     * @param WonderGroupFactory $wonderGroupFactory
     * @param \Twig_Environment $twig
     * @param WonderGroup $wonderGroupService
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     * @param UrlBuilder $urlBuilder
     * @param Wonder $wonderService
     * @param string $template
     * @param array $selectedMenu
     */
    public function __construct(
        Request $request,
        WonderGroupFactory $wonderGroupFactory,
        \Twig_Environment $twig,
        WonderGroup $wonderGroupService,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage,
        UrlBuilder $urlBuilder,
        Wonder $wonderService,
        $template = '',
        $selectedMenu = []
    ) {
        $this->request              = $request;
        $this->wonderGroupFactory   = $wonderGroupFactory;
        $this->twig                 = $twig;
        $this->wonderGroupService   = $wonderGroupService;
        $this->responseFactory      = $responseFactory;
        $this->flashMessage         = $flashMessage;
        $this->urlBuilder           = $urlBuilder;
        $this->wonderService        = $wonderService;
        $this->template             = $template;
        $this->selectedMenu         = $selectedMenu;
    }

    /**
     * @return string|\Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $wonderGroup = $this->wonderGroupService->getWonderGroup($id);
            if (!$wonderGroup) {
                $this->flashMessage->addErrorMessage("The score wonder group does not exit");
                return $this->responseFactory->create(
                    ResponseFactory::REDIRECT,
                    [
                        'url' => $this->urlBuilder->getUrl('/wonder-group/list')
                    ]
                );
            }
        } else {
            $wonderGroup = $this->wonderGroupFactory->create();
        }
        return $this->twig->render(
            $this->template,
            [
                'wonderGroup' => $wonderGroup,
                'wonders' => $this->wonderService->getWonders(),
                'selected' => $this->getSelectedWonders($wonderGroup),
                'selectedMenu' => $this->selectedMenu,
                'page_title' => $this->getPageTitle($wonderGroup)
            ]
        );
    }

    /**
     * @param \Wonders\WonderGroup $wonderGroup
     * @return string
     */
    private function getPageTitle(\Wonders\WonderGroup $wonderGroup)
    {
        if ($wonderGroup->getId()) {
            return "Edit WonderGroup: ".$wonderGroup->getName();
        }
        return "New WonderGroup";
    }

    /**
     * @param \Wonders\WonderGroup $wonderGroup
     * @return array|int
     */
    private function getSelectedWonders(\Wonders\WonderGroup $wonderGroup)
    {
        $selected = [];
        $wonders = $wonderGroup->getWonderGroupWonders();
        if ($wonders) {
            foreach ($wonders as $wonder) {
                $selected[] = $wonder->getWonderId();
            }
        }
        return $selected;
    }
}

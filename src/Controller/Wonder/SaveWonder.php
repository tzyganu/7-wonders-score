<?php
namespace Controller\Wonder;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\WonderFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;
use Wonders\Wonder;

class SaveWonder implements AuthInterface, ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var \Service\Wonder
     */
    private $wonderService;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var WonderFactory
     */
    private $wonderFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * SaveWonder constructor.
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param \Service\Wonder $wonderService
     * @param WonderFactory $wonderFactory
     * @param UrlBuilder $urlBuilder
     * @param FlashMessage $flashMessage
     */
    public function __construct(
        Request $request,
        ResponseFactory $responseFactory,
        \Service\Wonder $wonderService,
        WonderFactory $wonderFactory,
        UrlBuilder $urlBuilder,
        FlashMessage $flashMessage
    ) {
        $this->request          = $request;
        $this->responseFactory  = $responseFactory;
        $this->wonderService    = $wonderService;
        $this->wonderFactory    = $wonderFactory;
        $this->urlBuilder       = $urlBuilder;
        $this->flashMessage     = $flashMessage;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $wonder = $this->wonderService->getWonder($id);
                if (!$wonder) {
                    throw new \Exception("Wonder with id {$id} does not exist");
                }
            } else {
                $wonder = $this->wonderFactory->create();
            }
            $wonder->setName($this->request->get('name'));
            $this->wonderService->save($wonder);
            $this->flashMessage->addSuccessMessage("The wonder was saved");
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl('wonder/list')
                ]
            );
        } catch (\Exception $e) {
            $this->flashMessage->addErrorMessage($e->getMessage());
            $url = ($id) ? '/wonder/edit' : '/wonder/new';
            $params = ($id) ? ['id' => $id] : [];
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl($url, $params)
                ]
            );
        }
    }
}

<?php
namespace Controller\WonderGroup;

use Controller\AuthInterface;
use Controller\ControllerInterface;
use Model\Factory\WonderGroupFactory;
use Model\Factory\WonderGroupWonderFactory;
use Model\FlashMessage;
use Model\ResponseFactory;
use Model\Transaction;
use Model\UrlBuilder;
use Service\WonderGroupWonder;
use Symfony\Component\HttpFoundation\Request;
use Wonders\WonderGroup;

class SaveWonderGroup implements AuthInterface, ControllerInterface
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
     * @var \Service\WonderGroup
     */
    private $wonderGroupService;
    /**
     * @var WonderGroupFactory
     */
    private $wonderGroupFactory;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var WonderGroupWonder
     */
    private $wonderGroupWonderService;
    /**
     * @var WonderGroupWonderFactory
     */
    private $wonderGroupWonderFactory;
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * SaveWonderGroup constructor.
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param \Service\WonderGroup $wonderGroupService
     * @param WonderGroupFactory $wonderGroupFactory
     * @param UrlBuilder $urlBuilder
     * @param FlashMessage $flashMessage
     * @param WonderGroupWonder $wonderGroupWonderService
     */
    public function __construct(
        Request $request,
        ResponseFactory $responseFactory,
        \Service\WonderGroup $wonderGroupService,
        WonderGroupFactory $wonderGroupFactory,
        UrlBuilder $urlBuilder,
        FlashMessage $flashMessage,
        WonderGroupWonder $wonderGroupWonderService,
        WonderGroupWonderFactory $wonderGroupWonderFactory,
        Transaction $transaction
    ) {
        $this->request                  = $request;
        $this->responseFactory          = $responseFactory;
        $this->wonderGroupService       = $wonderGroupService;
        $this->wonderGroupFactory       = $wonderGroupFactory;
        $this->urlBuilder               = $urlBuilder;
        $this->flashMessage             = $flashMessage;
        $this->wonderGroupWonderService = $wonderGroupWonderService;
        $this->wonderGroupWonderFactory = $wonderGroupWonderFactory;
        $this->transaction              = $transaction;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $wonderGroup = $this->wonderGroupService->getWonderGroup($id);
                if (!$wonderGroup) {
                    throw new \Exception("Wonder Group with id {$id} does not exist");
                }
            } else {
                $wonderGroup = $this->wonderGroupFactory->create();
            }
            $wonderGroup->setName($this->request->get('name'));
            try {
                $this->transaction->begin();
                $this->wonderGroupService->save($wonderGroup);
                $newWonders = $this->request->get('wonders', []);
                $this->saveWonderRelations($wonderGroup, $newWonders);
                $this->transaction->commit();
            } catch (\Exception $e) {
                $this->transaction->rollback();
                throw $e;
            }
            $this->flashMessage->addSuccessMessage("The score Wonder Group was saved");
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl("wonder-group/list")
                ]
            );
        } catch (\Exception $e) {
            $this->flashMessage->addErrorMessage($e->getMessage());
            $url = ($id) ? 'wonder-group/edit' : 'wonder-group/new';
            $params = ($id) ? ['id' => $id] : [];
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                [
                    'url' => $this->urlBuilder->getUrl($url, $params)
                ]
            );
        }
    }

    /**
     * @param WonderGroup $wonderGroup
     * @param $newValues
     */
    private function saveWonderRelations(WonderGroup $wonderGroup, $newValues)
    {
        $existing = $wonderGroup->getWonderGroupWonders();
        if ($existing) {
            foreach ($existing as $relation) {
                $this->wonderGroupWonderService->delete($relation);
            }
        }
        if (is_array($newValues)) {
            foreach ($newValues as $newValue) {
                $relation = $this->wonderGroupWonderFactory->create();
                $relation->setWonderGroupId($wonderGroup->getId());
                $relation->setWonderId($newValue);
                $this->wonderGroupWonderService->save($relation);
            }
        }
    }
}

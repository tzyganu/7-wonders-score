<?php
namespace Controller\Login;

use Controller\ControllerInterface;
use Model\FlashMessage;
use Model\ResponseFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class Logout implements ControllerInterface
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var FlashMessage
     */
    private $flashMessage;

    /**
     * Logout constructor.
     * @param Session $session
     * @param Request $request
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     */
    public function __construct(
        Session $session,
        Request $request,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage
    ) {
        $this->session          = $session;
        $this->request          = $request;
        $this->responseFactory  = $responseFactory;
        $this->flashMessage     = $flashMessage;
    }

    /**
     * @return Response
     */
    public function execute()
    {
        $this->session->set('user', null);
        $this->flashMessage->addSuccessMessage('You logged out successfully');
        return $this->responseFactory->create(ResponseFactory::REDIRECT, ['url' => $this->request->getBaseUrl().'/']);
    }
}

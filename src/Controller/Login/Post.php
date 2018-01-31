<?php
namespace Controller\Login;

use Controller\ControllerInterface;
use Model\FlashMessage;
use Model\Hash;
use Model\ResponseFactory;
use Model\UrlBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Wonders\User;

class Post implements ControllerInterface
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Hash
     */
    private $hash;
    /**
     * @var \Service\User
     */
    private $userService;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;
    /**
     * @var FlashMessage
     */
    private $flashMessage;
    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * Post constructor.
     * @param Request $request
     * @param Session $session
     * @param Hash $hash
     * @param \Service\User $userService
     * @param ResponseFactory $responseFactory
     * @param FlashMessage $flashMessage
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(
        Request $request,
        Session $session,
        Hash $hash,
        \Service\User $userService,
        ResponseFactory $responseFactory,
        FlashMessage $flashMessage,
        UrlBuilder $urlBuilder
    ) {
        $this->request          = $request;
        $this->session          = $session;
        $this->hash             = $hash;
        $this->userService      = $userService;
        $this->responseFactory  = $responseFactory;
        $this->flashMessage     = $flashMessage;
        $this->urlBuilder       = $urlBuilder;
    }

    /**
     * @return Response
     */
    public function execute()
    {
        if ($this->session->get('user')) {
            return $this->responseFactory->create(ResponseFactory::REDIRECT, ['url' => $this->urlBuilder->getUrl('')]);
        }
        try {
            $username = $this->request->get('username');
            $password = $this->request->get('password');
            if (!$username || !$password) {
                throw new \Exception('Username and password should not me empty');
            }
            $user = $this->userService->getUserByUsername($username);
            if (!$user) {
                throw new \Exception("User not found");
            }
            if ($user->getPassword() == $this->hash->hash($password) && $user->getActive() == User::STATUS_ACTIVE) {
                $this->flashMessage->addSuccessMessage('Welcome '. $user->getUsername());
                $this->session->set('user', $user);
                return $this->responseFactory->create(ResponseFactory::REDIRECT, ['url' => $this->getRedirectUrl()]);
            }
            throw new \Exception("Wrong password or not active user");
        } catch (\Exception $e) {
            $this->flashMessage->addErrorMessage($e->getMessage());
            return $this->responseFactory->create(
                ResponseFactory::REDIRECT,
                ['url' => $this->urlBuilder->getUrl('login')]
            );
        }
    }

    /**
     * @return string
     */
    private function getRedirectUrl()
    {
        $redirect = $this->request->get('back', null);
        if ($redirect === null) {
            return $this->urlBuilder->getUrl('');
        }
        return $this->urlBuilder->getUrl(base64_decode($redirect));
    }
}

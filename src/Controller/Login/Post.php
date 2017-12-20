<?php
namespace Controller\Login;

use Controller\BaseController;
use Factory\UserQuery;
use Model\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Post extends BaseController
{
    /**
     * @var UserQuery
     */
    protected $userQueryFactory;
    /**
     * @var Session
     */
    protected $session;
    /**
     * @var Hash
     */
    protected $hash;

    /**
     * Post constructor.
     * @param Request $request
     * @param Session $session
     * @param Hash $hash
     * @param UserQuery $userQueryFactory
     */
    public function __construct(
        Request $request,
        Session $session,
        Hash $hash,
        UserQuery $userQueryFactory
    ) {
        $this->session = $session;
        $this->userQueryFactory = $userQueryFactory;
        $this->hash = $hash;
        parent::__construct($request);
    }

    public function execute()
    {
        $username = $this->request->get('username');
        $password = $this->request->get('password');
        if (!$username || !$password) {
            return [];
        }
        $user = $this->userQueryFactory->create()
            ->findOneByUsername($username);
        if (!$user) {
            //throw new \Exception("User not found");
            return new RedirectResponse($this->request->getBaseUrl().'/login');
        }
        if ($user->getPassword() == $this->hash->hash($password) && $user->getActive() == 1) {
            $this->session->set('user', $user);
            return [];
        }
        //throw new \Exception("Wrong password or not active user");
        return new RedirectResponse($this->request->getBaseUrl().'/login');
    }
}

<?php
namespace Controller\Login;

use Controller\BaseController;
use Model\Hash;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Wonders\Base\UserQuery;

class Post extends BaseController
{

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
     */
    public function __construct(
        Request $request,
        Session $session,
        Hash $hash
    ) {
        $this->hash = $hash;
        parent::__construct($request, $session);
    }

    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        try {
            $username = $this->request->get('username');
            $password = $this->request->get('password');
            if (!$username || !$password) {
                throw new \Exception('Username and password should not me empty');
            }
            $user = UserQuery::create()
                ->findOneByUsername($username);
            if (!$user) {
                throw new \Exception("User not found");
            }
            if ($user->getPassword() == $this->hash->hash($password) && $user->getActive() == 1) {
                $this->session->set('user', $user);
                return new RedirectResponse($this->request->getBaseUrl() . '/');
            }
            throw new \Exception("Wrong password or not active user");
        } catch (\Exception $e) {
            $this->addFlashMessage(self::FLASH_MESSAGE_ERROR, $e->getMessage());
            return new RedirectResponse($this->request->getBaseUrl() . '/login');
        }
    }
}

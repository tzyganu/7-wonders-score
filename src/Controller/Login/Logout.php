<?php
namespace Controller\Login;

use Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Logout extends BaseController
{
    protected $session;
    public function __construct(
        Request $request,
        Session $session
    ) {
        $this->session = $session;
        parent::__construct($request);
    }

    public function execute()
    {
        $this->session->set('user', null);
    }
}

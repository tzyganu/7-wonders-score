<?php
namespace Controller\Login;

use Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Index extends BaseController
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * Index constructor.
     * @param Request $request
     * @param Session $session
     */
    public function __construct(
        Request $request,
        Session $session
    ) {
        $this->session = $session;
        parent::__construct($request);
    }

    /**
     * @return array|RedirectResponse
     */
    public function execute()
    {
        if ($this->session->get('user')) {
            $url = $this->request->getBaseUrl().'/';
            return new RedirectResponse($url);
        }
        return [];
    }
}

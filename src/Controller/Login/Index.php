<?php
namespace Controller\Login;

use Controller\OutputController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Index extends OutputController
{
    /**
     * @var string
     */
    protected $template = 'login/index.html.twig';
    /**
     * @return string|RedirectResponse
     */
    public function execute()
    {
        if ($this->session->get('user')) {
            $url = $this->request->getBaseUrl().'/';
            return new RedirectResponse($url);
        }
        return $this->render([]);
    }
}

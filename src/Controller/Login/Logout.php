<?php
namespace Controller\Login;

use Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Logout extends BaseController
{
    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        $this->session->set('user', null);
        $this->addFlashMessage(self::FLASH_MESSAGE_SUCCESS, 'You logged out successfully');
        return new RedirectResponse($this->request->getBaseUrl().'/');
    }
}

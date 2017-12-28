<?php
namespace Controller\Player;

use Controller\AuthInterface;
use Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Wonders\Player;
use Wonders\PlayerQuery;

class SavePlayer extends BaseController implements AuthInterface
{
    /**
     * @return RedirectResponse
     */
    public function execute()
    {
        $id = $this->request->get('id');
        try {
            if ($id) {
                $player = PlayerQuery::create()
                    ->findOneById($id);
            } else {
                $player = new Player();
            }
            $player->setName($this->request->get('name'));
            $player->save();
            $this->addFlashMessage(self::FLASH_MESSAGE_SUCCESS, 'The player was saved');
            return new RedirectResponse($this->request->getBaseUrl().'/player/list');
        } catch (\Exception $e) {
            $this->addFlashMessage(self::FLASH_MESSAGE_ERROR, $e->getMessage());
            if ($id) {
                return new RedirectResponse($this->request->getBaseUrl() . '/player/edit?id=' . $id);
            }
            return new RedirectResponse($this->request->getBaseUrl() . '/player/edit');
        }
    }
}

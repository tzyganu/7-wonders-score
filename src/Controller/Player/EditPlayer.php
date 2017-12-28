<?php
namespace Controller\Player;

use Controller\AuthInterface;
use Controller\OutputController;
use Wonders\Player;
use Wonders\PlayerQuery;

class EditPlayer extends OutputController implements AuthInterface
{
    /**
     * @var string
     */
    protected $selectedMenu = 'players';
    /**
     * @var string
     */
    protected $template = 'player/edit.html.twig';

    /**
     * @return string
     */
    public function execute()
    {
        $id = $this->request->get('id');
        if ($id) {
            $player = PlayerQuery::create()
                ->findOneById($id);
            if ($player) {
                return $this->render(['player' => $player]);
            }
        }
        return $this->render(['player' => new Player()]);
    }
}

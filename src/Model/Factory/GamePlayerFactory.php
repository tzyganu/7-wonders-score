<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\GamePlayer;

class GamePlayerFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * GameFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $data
     * @return GamePlayer
     */
    public function create(array $data = [])
    {
        return $this->factory->create(GamePlayer::class, $data);
    }
}

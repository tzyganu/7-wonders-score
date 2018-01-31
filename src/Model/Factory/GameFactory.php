<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\Game;

class GameFactory
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
     * @return Game
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Game::class, $data);
    }
}

<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\Player;

class PlayerFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * PlayerFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $data
     * @return Player
     */
    public function create(array $data = [])
    {
        return $this->factory->create(Player::class, $data);
    }
}

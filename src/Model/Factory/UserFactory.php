<?php
namespace Model\Factory;

use Model\Factory;
use Wonders\User;

class UserFactory
{
    /**
     * @var Factory
     */
    private $factory;
    /**
     * UserFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param $data
     * @return User
     */
    public function create(array $data = [])
    {
        return $this->factory->create(User::class, $data);
    }
}

<?php
namespace Model\Query;

use Model\Factory;
use Wonders\UserQuery;

class UserQueryFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * UserQueryFactory constructor.
     * @param Factory $factory
     */
    public function __construct(
        Factory $factory
    ) {
        $this->factory = $factory;
    }

    /**
     * @param array $data
     * @return UserQuery
     */
    public function create(array $data = [])
    {
        return $this->factory->create(UserQuery::class, $data);
    }
}

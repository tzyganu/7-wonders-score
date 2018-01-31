<?php
namespace Service;

use Model\Query\UserQueryFactory;
use Propel\Runtime\ActiveQuery\Criteria;
use Wonders\UserQuery;

class User
{
    /**
     * @var UserQueryFactory
     */
    private $userQueryFactory;
    /**
     * @var array
     */
    private $cache = [
        'id' => [],
        'username' => []
    ];

    /**
     * User constructor.
     * @param UserQueryFactory $userQueryFactory
     */
    public function __construct(
        UserQueryFactory $userQueryFactory
    ) {
        $this->userQueryFactory = $userQueryFactory;
    }

    /**
     * @param array $filter
     * @return mixed
     */
    public function getUsers($filter = [])
    {
        $gamesQuery = $this->userQueryFactory->create();
        if (count($filter)) {
            $gamesQuery->filterByArray($filter);
        }
        return $gamesQuery->find();

    }

    /**
     * @param $id
     * @return \Wonders\User
     */
    public function getUser($id)
    {
        if (!isset($this->cache['id'][$id])) {
            $user = $this->userQueryFactory->create()->findOneById($id);
            if ($user) {
                $this->cache['username'][$user->getUsername()] = $user;
            }
            $this->cache['id'][$id] = $user;
        }
        return $this->cache['id'][$id];
    }
    /**
     * @param $username
     * @return \Wonders\User
     */
    public function getUserByUsername($username)
    {
        if (!isset($this->cache['id'][$username])) {
            $user = $this->userQueryFactory->create()->findOneByUsername($username);
            if ($user) {
                $this->cache['id'][$user->getId()] = $user;
            }
            $this->cache['username'][$username] = $user;
        }
        return $this->cache['username'][$username];
    }

    /**
     * @param \Wonders\User $user
     * @return int
     */
    public function save(\Wonders\User $user)
    {
        return $user->save();
    }
}

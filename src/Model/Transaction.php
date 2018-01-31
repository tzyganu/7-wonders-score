<?php
namespace Model;

use Propel\Runtime\Propel;

class Transaction
{
    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface
     */
    private $conn;

    /**
     * @return \Propel\Runtime\Connection\ConnectionInterface
     */
    private function getConnection()
    {
        if ($this->conn === null) {
            $this->conn = Propel::getConnection();
        }
        return $this->conn;
    }

    /**
     * @return bool
     */
    public function begin()
    {
        return $this->getConnection()->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit()
    {
        return $this->getConnection()->commit();
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        return $this->getConnection()->rollBack();
    }
}

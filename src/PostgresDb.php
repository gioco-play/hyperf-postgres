<?php

namespace GiocoPlus\Postgres;

use GiocoPlus\Postgres\Exception\PostgresDbException;
use GiocoPlus\Postgres\Pool\PoolFactory;
use GiocoPlus\Postgres\Pool\PostgresDBPool;
use Hyperf\Utils\Context;
use Swoole\Coroutine\PostgreSQL;

/**
 * Class PostgresDb
 * @package GiocoPlus\PostgresDb
 */
class PostgresDb
{
    /**
     * @var PoolFactory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $poolName = 'default';

    public function __construct(PoolFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * 選擇連結池
     *
     * @param string $poolName
     * @return $this
     */
    public function setPool(string $poolName): PostgresDb {
        $this->poolName = $poolName;
        return $this;
    }

    /**
     * @param string $sql
     * @return mixed
     * @throws PostgresDbException
     */
    public function query(string $sql) {
        try {
            $collection = $this->getConnection();
            return $collection->query($sql);
        } catch (\Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * @param mixed $sql
     * @return mixed
     * @throws PostgresDbException
     */
    public function fetchAll($sql) {
        try {
            $collection = $this->getConnection();
            return $collection->fetchAll($sql);
        } catch (\Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * @param mixed $sql
     * @return mixed
     * @throws PostgresDbException
     */
    public function fetchRow($sql) {
        try {
            $collection = $this->getConnection();
            return $collection->fetchRow($sql);
        } catch (\Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        }
    }

    /**
     * PostgreSQL
     *
     * @return PostgresDbConnection
     */
    public function getConnection()
    {
        $connection = null;
        $hasContextConnection = Context::has($this->getContextKey());
        if ($hasContextConnection) {
            $connection = Context::get($this->getContextKey());
        }
        if (!$connection instanceof PostgresDbConnection) {
            $pool = $this->factory->getPool($this->poolName);
            $connection = $pool->get()->getConnection();
        }
        return $connection;
    }

    /**
     * The key to identify the connection object in coroutine context.
     */
    private function getContextKey(): string
    {
        return sprintf('postgres.connection.%s', $this->poolName);
    }

}
<?php

namespace GiocoPlus\Postgres;

use Exception;
use GiocoPlus\Postgres\Exception\PostgresDbException;
use Hyperf\Contract\ConnectionInterface;
use Hyperf\Pool\Connection;
use Hyperf\Pool\Exception\ConnectionException;
use Hyperf\Pool\Pool;
use Psr\Container\ContainerInterface;
use Swoole\Coroutine\PostgreSQL;

class PostgresDbConnection extends Connection implements ConnectionInterface
{
    /**
     * @var Manager
     */
    protected $connection;

    /**
     * @var array
     */
    protected $config;

    public function __construct(ContainerInterface $container, Pool $pool, array $config)
    {
        parent::__construct($container, $pool);
        $this->config = $config;
        $this->reconnect();
    }

    public function getActiveConnection()
    {
        if (!$this->reconnect()) {
            throw new ConnectionException('Connection reconnect failed.');
        }
        return $this;
    }

    /**
     * Reconnect the connection.
     */
    public function reconnect(): bool
    {
        try {
            $configuration = new PostgresDbConfiguration($this->config);
            $this->connection = new PostgreSQL();
            $status = $this->connection->connect($configuration->getDsn());
            if (!$status) {
                throw new Exception($this->connection->error);
            }
        } catch (Exception $e) {
            throw PostgresDbException::managerError('postgres 连接参数错误:' . $e->getMessage());
        }
        $this->lastUseTime = microtime(true);
        return true;
    }

    /**
     * Close the connection.
     */
    public function close(): bool
    {
        // TODO: Implement close() method.
        return true;
    }
}
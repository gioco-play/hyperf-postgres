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
     * @var PostgreSQL
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
        // TODO: Implement getActiveConnection() method.
        if ($this->check()) {
            return $this;
        }
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

    /**
     * @param string $sql
     * @return mixed
     * @throws PostgresDbException
     */
    public function query(string $sql) {
        try {
            return $this->connection->query($sql);
        } catch (\Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        } catch (Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        } finally {
            $this->release();
        }
    }

    /**
     * @param mixed $sql
     * @return mixed
     * @throws PostgresDbException
     */
    public function fetchAll($sql) {
        try {
            return $this->connection->fetchAll($sql);
        } catch (\Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        } catch (Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        } finally {
            $this->release();
        }
    }

    /**
     * @param mixed $sql
     * @return mixed
     * @throws PostgresDbException
     */
    public function fetchRow($sql) {
        try {
            return $this->connection->fetchRow($sql);
        } catch (\Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        } catch (Exception $e) {
            throw new PostgresDbException($e->getFile() . $e->getLine() . $e->getMessage());
        } finally {
            $this->release();
        }
    }

    /**
     * 判断当前的数据库连接是否已经超时
     *
     * @return bool
     * @throws \MongoDB\Driver\Exception\Exception
     * @throws MongoDBException
     */
    public function check(): bool
    {
        try {
            $this->connection->query("SELECT setting FROM pg_settings WHERE  name = 'max_connections';");
            return true;
        } catch (\Throwable $e) {
            return $this->catchPostgresException($e);
        }
    }

    /**
     * @param \Throwable $e
     * @return bool
     * @throws MongoDBException
     */
    private function catchPostgresException(\Throwable $e)
    {
        switch ($e) {
            case ($e instanceof InvalidArgumentException):
            {
                throw PostgresDbException::managerError('postgres argument exception: ' . $e->getMessage());
            }
            case ($e instanceof AuthenticationException):
            {
                throw PostgresDbException::managerError('postgres数据库连接授权失败:' . $e->getMessage());
            }
            case ($e instanceof ConnectionException):
            {
                /**
                 * https://cloud.tencent.com/document/product/240/4980
                 * 存在连接失败的，那么进行重连
                 */
                for ($counts = 1; $counts <= 5; $counts++) {
                    try {
                        $this->reconnect();
                    } catch (\Exception $e) {
                        continue;
                    }
                    break;
                }
                return true;
            }
            case ($e instanceof RuntimeException):
            {
                throw PostgresDbException::managerError('postgres runtime exception: ' . $e->getMessage());
            }
            default:
            {
                throw PostgresDbException::managerError('postgres unexpected exception: ' . $e->getMessage());
            }
        }
    }
}
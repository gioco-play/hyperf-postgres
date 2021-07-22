<?php

namespace GiocoPlus\Postgres\Pool;

use Hyperf\Di\Container;
use Psr\Container\ContainerInterface;
use Swoole\Coroutine\Channel;

class PoolFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Channel[]
     */
    protected $pools = [];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getPool(string $name): PostgresDbPool
    {
        if (isset($this->pools[$name])) {
            return $this->pools[$name];
        }

        if ($this->container instanceof Container) {
            $pool = $this->container->make(PostgresDbPool::class, ['name' => $name]);
        } else {
            $pool = new PostgresDbPool($this->container, $name);
        }
        return $this->pools[$name] = $pool;
    }
}
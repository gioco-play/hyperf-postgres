<?php

declare(strict_types=1);


if (!function_exists('postgres_pool_config')) {

      /**
       * PostgreSQL 連結池
       *
       * @param string $host
       * @param string $dbName
       * @param integer $port
       * @param string $username
       * @param string $password
       * @param integer $maxConn
       * @param float $connTimeout
       * @param float $maxIdleTime
       * @return array
       */
    function postgres_pool_config(string $host, string $dbName, int $port = 5432,
        string $username, string $password,
        int $maxConn = 100, float $connTimeout = 10, float $maxIdleTime = 60 ): array {

        return [
            'username' => $username,
            'password' => $password,
            'host' => $host,
            'port' => $port,
            'db' => $dbName,
            'pool' => [
                'min_connections' => 1,
                'max_connections' => $maxConn,
                'connect_timeout' => $connTimeout,
                'wait_timeout' => 3.0,
                'heartbeat' => -1,
                'max_idle_time' => $maxIdleTime,
            ]
        ];
    }

}
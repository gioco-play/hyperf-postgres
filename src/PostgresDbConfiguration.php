<?php


namespace GiocoPlus\Postgres;

use  GiocoPlus\Postgres\Exception\InvalidPostgresDbConnectionException;

class PostgresDbConfiguration
{
    /**
     * @return array
     */
    public function getHost(): array
    {
        return $this->host;
    }

    /**
     * @param array $host
     * @return PostgresDbConfiguration
     */
    public function setHost(array $host): PostgresDbConfiguration
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return PostgresDbConfiguration
     */
    public function setPort(int $port): PostgresDbConfiguration
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return PostgresDbConfiguration
     */
    public function setUsername(string $username): PostgresDbConfiguration
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return PostgresDbConfiguration
     */
    public function setPassword(string $password): PostgresDbConfiguration
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param string $database
     * @return PostgresDbConfiguration
     */
    public function setDatabase(string $database): PostgresDbConfiguration
    {
        $this->database = $database;
        return $this;
    }

    /**
     * @return array
     */
    public function getPool(): array
    {
        return $this->pool;
    }

    /**
     * @param array $pool
     * @return PostgresDbConfiguration
     */
    public function setPool(array $pool): PostgresDbConfiguration
    {
        $this->pool = $pool;
        return $this;
    }

    /**
     * @var array
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $database;

    /**
     * @var array
     */
    private $pool;

    /**
     * @var array
     */
    protected $config;

    /**
     * PostgresDbConfiguration constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->host = $config['host'] ?? [];
        $this->port = $config['port'] ?? 5432;
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->database = $config['database'] ?? '';
        $this->pool = $config['pool'] ?? [];
    }

    public function getDsn()
    {
        if (!$this->getHost()) {
            throw new InvalidPostgresDbConnectionException('error postgres config host');
        }
        return "host={$this->host} port={$this->port} dbname={$this->database} user={$this->username} password={$this->password}";
    }
}

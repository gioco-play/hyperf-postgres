<?php

declare(strict_types=1);

namespace GiocoPlus\Postgres\Exception;

class PostgresException extends \Exception
{
    /**
     * @param string $msg
     * @throws PostgresException
     */
    public static function managerError(string $msg)
    {
        throw new self($msg);
    }
}
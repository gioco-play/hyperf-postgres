<?php

declare(strict_types=1);

namespace GiocoPlus\Postgres\Exception;

class PostgresDbException extends \Exception
{
    /**
     * @param string $msg
     * @throws PostgresDbException
     */
    public static function managerError(string $msg)
    {
        throw new self($msg);
    }
}
<?php

namespace GiocoPlus\Postgres;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                PostgreDb::class => \GiocoPlus\Postgres\PostgreDb::class,
            ],
            'commands' => [
            ],
            'scan' => [
                'paths' => [
                    __DIR__,
                ],
            ],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config of postgres client.',
                    'source' => __DIR__ . '/publish/postgres.php',
                    'destination' => BASE_PATH . '/config/autoload/postgres.php',
                ],
            ],
        ];
    }
}
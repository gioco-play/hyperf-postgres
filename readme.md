# hyperf postgresdb pool

```
composer require gioco-plus/hyperf-postgres

php bin/hyperf.php vendor:publish "gioco-plus/hyperf-postgres" 
```

# 動態切換連結池
```php
    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;


    /**
     * @Inject()
     * @var PostgreSQL
     */
    protected $postgresClient;


    
    # 使用方式
    $config =  postgres_pool_config('192.168.30.xx', 'ezadmin_yb', 5432, 'gf_db'); # 建立連結資訊
    $this->config->set("postgres.dbYB", $config); # 前綴 "postgresdb."

    $this->postgresClient->setPool("dbYB")->insert("hyperf_test", [
        'aaa'=>'a',
        'bbb'=>'b',
        'ccc'=>'c'
    ]);

```

## config 
在/config/autoload目录里面创建文件 postgres.php
添加以下内容
```php
return [
    'default' => [
        'username' => env('POSTGRES_USERNAME', ''),
        'password' => env('POSTGRES_PASSWORD', ''),
        'host' => env('POSTGRES_HOST', '127.0.0.1'),
        'port' => env('POSTGRES_PORT', 5432),
        'database' => env('POSTGRES_DB', 'test'),
        'pool' => [
            'min_connections' => 1,
            'max_connections' => 100,
            'connect_timeout' => 10.0,
            'wait_timeout' => 3.0,
            'heartbeat' => -1,
            'max_idle_time' => (float)env('POSTGRES_MAX_IDLE_TIME', 60),
        ],
    ],
];
```

<?php
declare(strict_types=1);

use DI\ContainerBuilder;

require __DIR__ . '/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/app/settings.php';
$settings($containerBuilder);

$container = $containerBuilder->build();
$settings = $container->get('settings');

return [
    'migration_dirs' => [
        'initialize' => __DIR__ . '/migrate/initialize',
        'dummy_data' => __DIR__ . '/migrate/dummy_data',
    ],
    'environments' => [
        'local' => [
            'adapter' => 'mysql',
            'host' => $settings['mysql']['host'],
            'port' => $settings['mysql']['port'],
            'username' => $settings['mysql']['user'],
            'password' => $settings['mysql']['password'],
            'db_name' => $settings['mysql']['database'],
            'charset' => $settings['mysql']['charset'],
        ],
    ],
    'default_environment' => 'local',
    'log_table_name' => 'phoenix_log',
];
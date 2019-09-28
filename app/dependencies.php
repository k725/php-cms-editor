<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Views\Twig;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            $sqlSettings = $c->get('settings')['mysql'];

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;port=%d;charset=%s',
                $sqlSettings['host'],
                $sqlSettings['database'],
                $sqlSettings['port'],
                $sqlSettings['charset'],
            );
            $pdoOption = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true,
            ];

            $pdo = new PDO($dsn, $sqlSettings['user'], $sqlSettings['password'], $pdoOption);
            return $pdo;
        },
        Twig::class => function (ContainerInterface $c) {
            $view = new Twig(__DIR__ . '/../templates', [
                'cache' => __DIR__ . '/../cache/Twig',
                'debug' => true,
                'auto_reload' => true,
            ]);
            return $view;
        },
    ]);
};

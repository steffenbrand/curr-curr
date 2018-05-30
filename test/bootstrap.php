<?php

declare(strict_types=1);

if (!is_file(__DIR__ . '/../vendor/autoload.php')) {
    throw new \LogicException('Run "composer install" to create autoloader.');
}

require_once __DIR__ . '/../vendor/autoload.php';
<?php

if (!is_file($autoloadFile = __DIR__ . '/../vendor/autoload.php')) {
    throw new \LogicException('Run "composer install" to create autoloader.');
}
require_once $autoloadFile;
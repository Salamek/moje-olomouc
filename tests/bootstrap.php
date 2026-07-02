<?php

declare(strict_types=1);

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Salamek\Tests', __DIR__ . '/src');

require_once __DIR__.'/Salamek/Tests/MojeOlomouc/BaseTest.php';

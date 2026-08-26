<?php

$loader = require dirname(__DIR__, 3).'/vendor/autoload.php';

$loader->addPsr4('Azuriom\\Plugin\\ReachUs\\', dirname(__DIR__).'/src/');
$loader->addPsr4('Azuriom\\Plugin\\ReachUs\\Tests\\', __DIR__.'/');

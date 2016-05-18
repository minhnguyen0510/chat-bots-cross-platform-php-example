<?php

$config = [];
if (file_exists(__DIR__.'/config.php')) {
    $config = include __DIR__.'/config.php';
}
require_once(dirname(__FILE__) . '/vendor/autoload.php');

use ChatBot;

$app = new ExampleBot($config);
$app->run();
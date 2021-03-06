<?php

use FFormula\RobotSharpApi\System;

if (!isset($path)) $path = '../';
include $path . 'vendor/autoload.php';

if (!file_exists($path . 'vendor/autoload.php'))
    die('run "composer update"');

if (!file_exists($path . 'config/pdo.php'))
    die('Copy "config/pdo.php.default" to "config/pdo.php" and edit it');

if (!file_exists($path . 'config/robot.php'))
    die('Copy "config/robot.php.default" to "config/robot.php" and edit it');

System\Log::set('RobotApi', $path . '/log/api.log');
System\Log::get()->info('IP: ' . $_SERVER['REMOTE_ADDR'] . ' ====================================');
System\Log::get()->info('Request: ' . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']);

System\DB::set(new System\PdoDb(require $path . 'config/pdo.php'));

System\Robot::init(require $path . 'config/robot.php');

$run = new System\Run();

echo $run->start($_REQUEST);

<?php

if (!isset($path)) $path = '../';

if (!file_exists($path . 'vendor/autoload.php'))
    die('run "composer update"');
include $path . 'vendor/autoload.php';

if (!file_exists($path . 'config/pdo.php'))
    die('Copy "config/pdo.php.default" to "config/pdo.php" and edit it');

FFormula\RobotSharpApi\System\Log::set(
    new Monolog\Logger('RobotWeb',
   [new Monolog\Handler\StreamHandler($path . '/log/web.log', Monolog\Logger::DEBUG)]));

FFormula\RobotSharpApi\System\DB::set(
    new FFormula\RobotSharpApi\System\PdoDb(
        require $path . 'config/pdo.php'));

$url = $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
\FFormula\RobotSharpApi\System\Log::get()->info('IP: ' . $_SERVER['REMOTE_ADDR'] . ' ====================================');
\FFormula\RobotSharpApi\System\Log::get()->info('Request: ' . $url);

echo
    (new FFormula\RobotSharpApi\Api\Run())
        ->start($_REQUEST);

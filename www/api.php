<?php

if (!isset($path)) $path = '../';

if (!file_exists($path . 'vendor/autoload.php'))
    die('run "composer update"');
include $path . 'vendor/autoload.php';

if (!file_exists($path . 'config/pdo.php'))
    die('Copy "config/pdo.php.default" to "config/pdo.php" and edit it');

FFormula\RobotSharpApi\System\DB::set(
    new FFormula\RobotSharpApi\System\PdoDb(
        require $path . 'config/pdo.php'));

echo
    (new FFormula\RobotSharpApi\Api\Run())
        ->start($_GET, $_POST);

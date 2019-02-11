<?php

if (!isset($path)) $path = '../';

if (!file_exists($path . 'vendor/autoload.php'))
    die('run "composer update"');
include $path . 'vendor/autoload.php';

if (!file_exists($path . 'config/pdo.php'))
    die('Copy "config/pdo.php.default" to "config/pdo.php" and edit it');
$db = new FFormula\RobotSharpApi\System\PdoDB(require $path . 'config/pdo.php');

echo (new FFormula\RobotSharpApi\System\Run())->start($_GET, $_POST);

<?php

if (!file_exists('../vendor/autoload.php'))
    die('run "composer update"');
include '../vendor/autoload.php';

if (!file_exists('../config/pdo.php'))
    die('Copy "config/pdo.php.default" to "config/pdo.php" and edit it');
$db = new FFormula\RobotSharpApi\System\PdoDB(require '../config/pdo.php');

echo (new FFormula\RobotSharpApi\System\Run())->start($_GET, $_POST);

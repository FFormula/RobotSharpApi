<?php

if (!file_exists('../vendor/autoload.php')) die('run "composer update"');
include '../vendor/autoload.php';

if (!file_exists('../config.php')) die('Copy "config.default.php" to "config.php" and edit it');
$config = require '../config.php';
$db = new FFormula\RobotSharp\Service\PdoDB($config['pdo']);
echo (new FFormula\RobotSharp\ApiSystem\Run())->start($_GET, $_POST);

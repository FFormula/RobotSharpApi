<?php

if (!file_exists('config.php'))
    die('Copy www/config.default.php to www/config.php and configure it');

include 'config.php';
include '../vendor/autoload.php';

$pdo = new PDO(PDO_DSN, PDO_USER, PDO_PASS);
$db = new FFormula\RobotSharp\Service\PdoDB($pdo);

echo (new FFormula\RobotSharp\ApiSystem\Client())->login($_GET);
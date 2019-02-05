<?php

include 'config.php';
include '../vendor/autoload.php';

$pdo = new PDO(PDO_DSN, PDO_USER, PDO_PASS);
$db = new FFormula\RobotSharp\Service\PdoDB($pdo);

echo (new FFormula\RobotSharp\ApiSystem\Run())->start($_GET, $_POST);
<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
define($_SERVER['REQUEST_METHOD'], json_decode(file_get_contents('php://input'), true));

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/utils/utils.php';
require_once __DIR__ . '/routes.php';

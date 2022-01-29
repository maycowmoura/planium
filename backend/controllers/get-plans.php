<?php

require_once __DIR__ . '/../models/JsonDB.php';

$db = new JsonDB('plans');
$result = $db->selectAll();

die(_json_encode($result));

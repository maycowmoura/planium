<?php

$router = new \Bramus\Router\Router();

$router->get('/plans', function () {
  require_once __DIR__ . '/controllers/get-plans.php';
});

$router->post('/budgets', function () {
  require_once __DIR__ . '/controllers/get-budget.php';
});

$router->get('/budgets/download/{filename}', function ($filename) {
  require_once __DIR__ . '/controllers/download-budgets.php';
});

$router->set404(function () {
  echo "<h1>404</h1>";
});

$router->run();

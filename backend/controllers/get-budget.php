<?php

require_once __DIR__ . '/../models/JsonDB.php';
require_once __DIR__ . '/../models/AgeRanges.php';
require_once __DIR__ . '/../models/BestPrice.php';

use Respect\Validation\Validator as v;

/**
 * Validação dos dados
 */
try {
  v::ArrayType()->setName('Dados do orçamento')->check(POST);

  foreach (POST as $item) {
    v::key('name', v::stringType()->regex('/^[A-zÀ-ú\s]+$/')->length(3, 60))
      ->key('age', v::intVal()->lessThan(120)->positive())
      ->key('planId', v::intVal()->positive())
      ->check($item);
  }
} catch (Exception $e) {
  error($e->getMessage());
}


$lifesByPlan = array_reduce(POST, function ($plans, $person) {
  $plans[$person['planId']] = ($plans[$person['planId']] ?? 0) + 1;
  return $plans;
}, []);


$budget = [
  'people' => [],
  'total' => 0
];

foreach (POST as $person) {
  $planId = $person['planId'];
  $ageRange = AgeRanges::getRange($person['age']);
  $lifes = $lifesByPlan[$planId];

  $bp = new BestPrice();
  $bp->setPlanId($planId);
  $bp->setAgeRange($ageRange);
  $bp->setTotalLifes($lifes);
  $price = $bp->getPrice();

  $budget['people'][] = [
    'name' => $person['name'],
    'age' => $person['age'],
    'planId' => $person['planId'],
    'price' => $price
  ];

  $budget['total'] += $price;
}

$budgetFilename = 'proposta_' . time_ms();
$budgetPath = __DIR__ . '/../budgets-dist';
is_dir($budgetPath) || mkdir($budgetPath);
$budget['file'] = $budgetFilename;

$fileContents = _json_encode($budget['people']);
file_put_contents("$budgetPath/$budgetFilename.json", $fileContents);

/**
 * Deleta propostas com mais de 30 dias
 */
$thirtyDaysAgo = time() - (30 * 24 * 60 * 60);
foreach(glob("$budgetPath/*") as $file){
  (filemtime($file) <= $thirtyDaysAgo) && unlink($file);
}

die(_json_encode($budget));

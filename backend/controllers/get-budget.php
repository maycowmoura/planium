<?php

require_once __DIR__ . '/../models/JsonDB.php';
require_once __DIR__ . '/../models/AgeRanges.php';
require_once __DIR__ . '/../models/BestPrice.php';

$lifesByPlan = array_reduce(POST, function($plans, $person){
  $plans[$person['planId']] = ($plans[$person['planId']] ?? 0) + 1;
  return $plans;
}, []);


$budget = [
  'persons' => [],
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

  $budget['persons'][] = [
    'name' => $person['name'],
    'age' => $person['age'],
    'planId' => $person['planId'],
    'price' => $price
  ];

  $budget['total'] += $price;
}

// salva o json dos beneficiarios
$db = new JsonDB('beneficiaries');
$db->update([[
  'persons' => $budget['persons'],
  'createdAt' => time()
]]);
$db->save();



die(_json_encode($budget));

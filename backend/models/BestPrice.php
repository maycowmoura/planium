<?php

require_once __DIR__ . '/JsonDB.php';

class BestPrice {
  private int $planId;
  private string $ageRange;
  private int $totalLifes;
  private array $pricesDb;

  function __construct() {
    $this->pricesDb = (new JsonDB('prices'))->selectAll();
  }

  public function setPlanId(int $planId) {
    $this->planId = $planId;
  }

  public function setAgeRange(string $ageRange) {
    $this->ageRange = $ageRange;
  }

  public function setTotalLifes(int $totalLifes) {
    $this->totalLifes = $totalLifes;
  }

  public function getPrice() {
    $avaliablePlans = array_filter($this->pricesDb, fn ($item) => $item['codigo'] == $this->planId);
    $avaliablePlans = array_values($avaliablePlans); // reseta o index do array filtrado

    // caso tenha mais que uma faixa de preço, pega a com melhor preço para o número de vidas
    if (count($avaliablePlans) > 1) {
      $initial = ['plan' => null, 'lifes' => 1];

      $bestPlan = array_reduce($avaliablePlans, function ($currentPlan, $plan) {
        $hasAllRequiredLifes = $this->totalLifes >= $plan['minimo_vidas'];
        $isBetterThanCurrentPlan = $plan['minimo_vidas'] >= $currentPlan['lifes'];

        if ($hasAllRequiredLifes && $isBetterThanCurrentPlan) {
          $currentPlan = ['plan' => $plan, 'lifes' => $plan['minimo_vidas']];
        }

        return $currentPlan;
      }, $initial);
      
      return $bestPlan['plan'][$this->ageRange];

    // caso só tenha um plano, retorna ele
    } else {
      return $avaliablePlans[0][$this->ageRange];
    }
  }
}

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

  /**
   * Seta o id do plano
   */
  public function setPlanId(int $planId) {
    $this->planId = $planId;
  }

  /**
   * Seta a faixa de idade
   * Não é a idade, sim a faixa, como "faixa1" ou "faixa2"
   */
  public function setAgeRange(string $ageRange) {
    $this->ageRange = $ageRange;
  }

  /**
   * Seta o total de pessoas que vão aderir a esse plano neste orçamento
   */
  public function setTotalLifes(int $totalLifes) {
    $this->totalLifes = $totalLifes;
  }

  /**
   * Obtém o melhor preço baseado na idade e no total de vidas
   */
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

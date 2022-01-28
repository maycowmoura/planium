<?php

class AgeRanges {
  public static function getRange(int $age): string {
    if ($age >= 0 && $age <= 17) {
      return 'faixa1';

    } elseif ($age >= 18 && $age <= 40) {
      return 'faixa2';

    } elseif ($age > 40) {
      return 'faixa3';
      
    } else {
      error('A idade não pode ser negativa.');
    }
  }
}

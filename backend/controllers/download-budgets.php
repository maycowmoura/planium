<?php

/**
 * 
 *  $filename vem da rota
 * 
 */

use Respect\Validation\Validator as v;
use OzdemirBurak\JsonCsv\File\Json;

v::key('type', v::in(['json', 'csv']))->check($_GET);

$fileType = $_GET['type'];
$filePath = __DIR__ . "/../budgetsDist/$filename.json";

if (!file_exists($filePath)) {
    error('O arquivo da proposta não existe.');
}

$budgetContents = file_get_contents($filePath);

if ($fileType == 'csv') {
    $json = new Json($filePath);
    $json->setConversionKey('utf8_encoding', true);
    $budgetContents = $json->convert();
    // o excel usa ; para separar colunas e , para separar float
    // como 99% vão abrir o csv no Excel, abaixo faz a substituição , = ; e . = ,
    $budgetContents = preg_replace(['/\,/', '/\./'], [';', ','], $budgetContents);
    // traduz o cabeçalho
    $budgetContents = str_replace(
        ['name', 'age', 'planId', 'price'],
        ['Beneficiário', 'Idade', 'Código do Plano', 'Preço'],
        $budgetContents
    );
}


header("Content-disposition: attachment; filename=\"Proposta Planium.$fileType\"");
header("Content-Type: application/$fileType");
header('Content-Length: ' . strlen($budgetContents));
header('Connection: close');
echo $budgetContents;

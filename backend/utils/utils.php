<?php

date_default_timezone_set('America/Sao_Paulo');

function _json_encode($array) {
  return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
}

function _json_decode($string) {
  return json_decode($string, true);
}

function time_ms(){
  return round(microtime(true) * 1000);
}

if (!getenv('DEV_MODE')) {

  header('content-type: application/json; charset=utf-8');

  set_exception_handler(function ($e) {
    return error($e->getMessage());
  });

  set_error_handler(function ($n, $errstr, $f, $errline) {
    return error($errstr, $errline);
  });
}

function error($errstr, $errline = null) {
  $line = $errline ? $errline . ': ' : '';
  die(_json_encode([
    'error' => $line . $errstr
  ]));
};

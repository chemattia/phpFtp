<?php

// Check QUERY_STRING -- inutile in quanto se non fosse definito non ci sarebbe form
/*
if (!isset($_POST["id_gruppo"]))
{
  $error = "Id gruppo non definito";
  require_once __DIR__ . '/html/error.php';
  exit;
}
*/
$nome_file = htmlspecialchars($_POST["id_gruppo"]);

// Check file
if (!file_exists(__DIR__ . '/files/' . $nome_file . '.txt'))
{
  $error = "File dati mancante";
  require_once __DIR__ . '/html/error.php';
  exit;
}

$file_txt = fopen(__DIR__ . '/files/' . $nome_file . '.txt', "r");
$file_csv = fopen(__DIR__ . '/files/'. $nome_file .'.csv', "w");

while(!feof($file_txt)) {
    $line = fgets($file_txt);
    $line_with_comma = substr($line,0,20). ', ' . substr($line,20,20) . ', ' . substr($line,40,20) . ', ' . substr($line,60);
    $line_with_comma = str_replace(' ', '', $line_with_comma);
    fputcsv($file_csv,explode(',',$line_with_comma));
}

fclose($file_txt);
fclose($file_csv);
header("Location: /index.php");
die();

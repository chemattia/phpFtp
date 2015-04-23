<?php

ini_set('display_errors', Off);
ini_set('error_reporting', E_NONE);

$format = filter_input(INPUT_GET, 'format', FILTER_SANITIZE_STRING) ?: "html";
if (!in_array($format, array("html", "json")))
{
    $format = "html";
}

// Check configuration
if (!file_exists(__DIR__ . '/config.php')) {
    $error = "File di configurazione mancante (vedi config.php)";
    require_once __DIR__ . "/templates/error.$format.php";
    exit;
}
require_once __DIR__ . '/config.php';

// Dummy response
if ($config['dummy_process']) {
    $message = 'File scaricato correttamente';
    require_once __DIR__ . "/templates/success.$format.php";
    exit;
}

// Check request
$id_gruppo = filter_input(INPUT_GET, 'id_gruppo', FILTER_VALIDATE_FLOAT);
if (empty($id_gruppo)) {
    $error = 'ID gruppo non definito o non valido';
    require_once __DIR__ . "/templates/error.$format.php";
    exit;
}

// Check file
if (!file_exists(__DIR__ . '/files/' . $id_gruppo . '.txt')) {
    $error = "File dati mancante";
    require_once __DIR__ . "/templates/error.$format.php";
    exit;
}

require_once __DIR__ . '/classes/csvconverter.php';
$csvconverter = new CsvConverter($id_gruppo);
$csvconverter->convert();

$message = "File elaborato correttamente";
require_once __DIR__ . "/templates/success.$format.php";

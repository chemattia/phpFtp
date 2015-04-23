<?php

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

// Check request
$id_gruppo = filter_input(INPUT_GET, 'id_gruppo', FILTER_VALIDATE_FLOAT);
if (empty($id_gruppo)) {
    $error = 'ID gruppo non definito o non valido';
    require_once __DIR__ . "/templates/error.$format.php";
    exit;
}

// Get file
require_once __DIR__ . '/classes/ftp.php';
$ftp = new Ftp($config['ftp_host'], $config['ftp_user'], $config['ftp_password']);
if (!$ftp->downloadFtp($id_gruppo)) {
    $error = 'Impossibile scaricare il file';
    require_once __DIR__ . "/templates/error.$format.php";
    exit;
}

$message = 'File scaricato correttamente';
require_once __DIR__ . "/templates/success.$format.php";

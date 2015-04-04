<?php

// Check configuration
if (!file_exists(__DIR__ . '/config.php')) {
    $error = "File di configurazione mancante (vedi config.php)";
    require_once __DIR__ . '/html/error.php';
    exit;
}
require_once __DIR__ . '/config.php';

// Check request
$id_gruppo = filter_input(INPUT_GET, 'id_gruppo', FILTER_VALIDATE_FLOAT);
if (empty($id_gruppo)) {
    $error = 'ID gruppo non definito o non valido';
    require_once __DIR__ . '/html/error.php';
    exit;
}

// Get file
require_once __DIR__ . '/classes/ftp.php';
$ftp = new Ftp($host, $user, $password);
if (!$ftp->downloadFtp($id_gruppo)) {
    $error = 'Impossibile scaricare il file';
    require_once __DIR__ . '/html/error.php';
    exit;
}

require_once __DIR__ . '/html/form.php';

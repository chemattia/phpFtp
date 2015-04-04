<?php

// Check QUERY_STRING -- inutile in quanto se non fosse definito non ci sarebbe form
// [Franz] Meglio lasciare comunque un controllo. Questo file non sa cosa succede altrove,
// e può essere chiamato direttamente. Se non facciamo questo controllo, la riga 15 sotto dà errore
// Check request
$id_gruppo = filter_input(INPUT_POST, 'id_gruppo', FILTER_VALIDATE_FLOAT);
if (empty($id_gruppo)) {
    $error = 'ID gruppo non definito o non valido';
    require_once __DIR__ . '/html/error.php';
    exit;
}

$nome_file = htmlspecialchars($_POST["id_gruppo"]);

// Check file
if (!file_exists(__DIR__ . '/files/' . $nome_file . '.txt')) {
    $error = "File dati mancante";
    require_once __DIR__ . '/html/error.php';
    exit;
}

require_once __DIR__ . '/classes/csvconverter.php';
$csvconverter = new CsvConverter($nome_file);
$csvconverter->convert();

require_once __DIR__ . '/html/success.php';

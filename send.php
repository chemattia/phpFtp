<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

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

$nome_file = htmlspecialchars($id_gruppo);

// Get users
require_once __DIR__ . '/classes/csvconverter.php';
$csvconverter = new CsvConverter($nome_file);
$users = $csvconverter->getUsers();

require_once __DIR__ . '/classes/mailup/mailupwsmanage.php';
$mailUpWsManage = new MailUpWsManage();
$mailUpWsManage->LoginFromId($config['mailup_user'], $config['mailup_password'], $config['mailup_console_id']);

// Get lists
try {
    $mailup_lists = $mailUpWsManage->getLists();
} catch (Exception $ex) {
    $error = 'Errore in getLists: ' . $ex->getMessage();
    require_once __DIR__ . '/html/error.php';
    exit;
}
if (!array_key_exists($config['mailup_list_id'], $mailup_lists)) {
    $error = 'La lista richiesta non esiste';
    require_once __DIR__ . '/html/error.php';
    exit;
}

if ($config['debug'])
{
    echo "<h1>Lists</h1>";
    echo "<pre>" . print_r($mailup_lists, true) . "</pre>";
}

// Get groups
try {
    $mailup_groups = $mailUpWsManage->getGroups($config['mailup_list_id']);
} catch (Exception $ex) {
    $error = 'Errore in getGroups: ' . $ex->getMessage();
    require_once __DIR__ . '/html/error.php';
    exit;
}
if (in_array("Automatic $id_gruppo", $mailup_groups)) {
    $error = 'Il gruppo richiesto esiste già';
    require_once __DIR__ . '/html/error.php';
    exit;
}

if ($config['debug'])
{
    echo "<h1>Groups</h1>";
    echo "<pre>" . print_r($mailup_groups, true) . "</pre>";
}

// Create group
try {
    $mailup_group_id = $mailUpWsManage->createGroup($config['mailup_list_id'], "Automatic $id_gruppo");
} catch (Exception $ex) {
    $error = 'Errore in createGroup: ' . $ex->getMessage();
    require_once __DIR__ . '/html/error.php';
    exit;
}
if (empty($mailup_group_id))
{
    $error = 'Impossibile creare nuovo gruppo';
    require_once __DIR__ . '/html/error.php';
    exit;
}

if ($config['debug'])
{
    echo "<h1>Nuovo gruppo creato: $mailup_group_id</h1>";
}

require_once __DIR__ . '/classes/mailup/mailupwsimport.php';
$mailUpWsImport = new MailUpWsImport($config['mailup_url']);
$mailUpWsImport->Activate($config['mailup_user'], $config['mailup_password']);

// Import users
try {
    $mailUpWsImport->importUsers($config['mailup_list_id'], $config['mailup_list_guid'], $mailup_group_id, $users);
} catch (Exception $ex) {
    $error = 'Impossibile importare gli utenti: ' . $ex->getMessage();
    require_once __DIR__ . '/html/error.php';
    exit;
}

$message = "Importazione completata correttamente!";
require_once __DIR__ . '/html/success.php';
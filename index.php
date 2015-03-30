<?php
require __DIR__ . '/connection/ftp.php';

$host = '';
$user = '';
$password = '';
parse_str($_SERVER['QUERY_STRING']);

if (!isset($id_gruppo)) {
    require_once __DIR__ . '/html/error_no_group.php';
    exit;
}

$id_gruppo = (htmlspecialchars($id_gruppo, ENT_QUOTES));
$ftp = new Ftp($host, $user, $password);
if (!$ftp->downloadFtp($id_gruppo) == true) {
    require_once __DIR__ . '/html/error_no_file.php';
    exit;
}

require_once __DIR__ . '/html/form.php';

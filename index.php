<?php
require 'connection/ftp.php';
require 'html/error.php';
require 'html/form.php';

$host = 'host';
$user = 'user';
$password = 'password';
parse_str($_SERVER['QUERY_STRING']);
if (!isset($id_gruppo)) {
    printError();
} else {
    $id_gruppo = (htmlspecialchars($id_gruppo, ENT_QUOTES));
    $ftp = new Ftp($host, $user, $password);
    if ($ftp->downloadFtp($id_gruppo) == true) {
        printForm($id_gruppo);
     }
}
?>

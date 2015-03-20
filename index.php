<?php
require 'connection/ftp.php';
parse_str($_SERVER['QUERY_STRING']);
if (isset($id_gruppo)) {
    $id_gruppo = (htmlspecialchars($id_gruppo, ENT_QUOTES));
    $ftp = new Ftp();
    $ftp->setConnection('', '', '');
    if ($ftp->downloadFtp($id_gruppo) == true) {
        echo 'id gruppo = ' . $id_gruppo . '<br>';
        ?>
        <form>
            <input type="submit" value="Submit">
        </form>
        <?php
    }
}
?>

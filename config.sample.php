<?php

/**
 * Esempio di file di configurazione. Rinominalo in config.php e inserisci i dati corretti
 */

$config = array(

    // if enabled, additional debug messages will be collected
    'debug' => false,

    // if enabled, the file is NOT actually downloaded and a fake "success"
    // response is returned
    'dummy_download' => false,

    // if enabled, the file is NOT actually processed and a fake "success"
    // response is returned
    'dummy_process' => false,

    // if enabled, the recipients are NOT actually imported into Mailup and a
    // fake success response is returned
    'dummy_import' => false,

    // FTP server where the source TXT file are downloaded from
    'ftp_host' => '91.187.200.88',

    // FTP user
    'ftp_user' => 'tmpphpftp',

    // FTP password
    'ftp_password' => 'e2t6r6t9p3m7m7t',
    
    'mailup_user' => "a9362",
    'mailup_password' => "gardainf2012",
    'mailup_url' => "http://news.anmvi.it",
    'mailup_console_id' => "9362",
    'mailup_list_id' => "20",
    'mailup_list_guid' => "191df641-87f4-400d-9889-baa24208654f",
);
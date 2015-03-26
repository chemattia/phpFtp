<?php

class Ftp
{
    private $host;
    private $user;
    private $password;

    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    public function downloadFtp($name_file)
    {
        $connection = ftp_connect($this->host);
        $login = ftp_login($connection, $this->user, $this->password);
        if (!$connection || !$login) {
            return false;
        }

        $local_file = 'files/' . $name_file . '.txt';
        $server_file = $name_file . '.txt';

        // Semplifichiamo
        return ftp_get($connection, $local_file, $server_file, FTP_BINARY);
        //if (ftp_get($connection, $local_file, $server_file, FTP_BINARY)) {
        //    return true;
        //} else {
        //    return false;
        //}

        // Qui non ci arriviamo mai, visto che fai "return" prima
        //ftp_close($connection);
    }

}

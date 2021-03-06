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
        if (!$connection)
        {
            echo "Impossibile connettersi all'host<br>";
            return false;
        }

        $login = ftp_login($connection, $this->user, $this->password);
        if (!$login) {
            echo "Errore di autenticazione FTP";
            return false;
        }

        $local_file = 'files/' . $name_file . '.txt';
        $server_file = $name_file . '.txt';

        return ftp_get($connection, $local_file, $server_file, FTP_BINARY);
    }

}

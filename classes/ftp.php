<?php

class Ftp
{
    private $host;
    private $path;
    private $user;
    private $password;

    public function __construct($host, $path, $user, $password)
    {
        $this->host = $host;
        $this->path = $path;
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

        $local_file = __DIR__ . '/../files/' . $name_file . '.txt';
        $server_file = $this->path . $name_file . '.txt';

        return ftp_get($connection, $local_file, $server_file, FTP_BINARY);
    }

}

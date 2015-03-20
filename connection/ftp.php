<?php

class Ftp
{
    private $host;
    private $user;
    private $password;

    public function setConnection($host, $user, $password)
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
        else {
            $local_file = 'files/' . $name_file . '.txt';
            $server_file = $name_file . '.txt';
            if (ftp_get($connection, $local_file, $server_file, FTP_BINARY)) {
                return true;
            } else {
                return false;
            }
            ftp_close($connection);
        }
    }

}

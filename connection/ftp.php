<?php
$host = '';
$user = '';
$password = '';

function downloadFtp($name_file)
{
  global $host, $user, $password;
  $connection = ftp_connect($host);
  $login = ftp_login($connection, $user, $password);
  if(!$connection || !$login){
      return false;
  } else {
      $local_file = 'files/'.$name_file.'.txt';
      $server_file = $name_file.'.txt';
      if (ftp_get($connection, $local_file, $server_file, FTP_BINARY)) {
          return true;
      } else {
          return false;
      }
      ftp_close($connection);
  }
}

?>

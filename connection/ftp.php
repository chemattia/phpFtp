<?php
$host = 'ftp.donkeylab.com';
$user = '4634301@aruba.it';
$password = 'vrs38lg7r7';

function downloadFtp($name_file)
{
  global $host, $user, $password;
  $connection = ftp_connect($host);
  $login = ftp_login($connection, $user, $password);

  if(!$connection || !$login){
      return false;
  } else {

      $local_file = 'files/'.$name_file.'.txt';
      $server_file = 'www.donkeylab.com/'.$name_file.'.txt';

      if (ftp_get($connection, $local_file, $server_file, FTP_BINARY)) {
          return true;
      } else {
          return false;
      }
      ftp_close($connection);
  }
}

?>

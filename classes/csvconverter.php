<?php

class CsvConverter
{
    private $txt_file;
    private $csv_file;

    public function __construct($file_name)
    {
        $this->txt_file = __DIR__ . "/../files/$file_name.txt";
        $this->csv_file = __DIR__ . "/../files/$file_name.csv";
    }

    public function convert()
    {
        $txt_file_handler = fopen($this->txt_file, "r");
        $csv_file_handler = fopen($this->csv_file, "w");
        while (!feof($txt_file_handler)) {
            fputcsv($csv_file_handler, $this->toCsv(fgets($txt_file_handler)), ',', '"');
        }

        fclose($txt_file_handler);
        fclose($csv_file_handler);

        return true;
    }

    private function toCsv($line)
    {
        return array(
            trim(substr($line, 0, 60)),
            trim(substr($line, 60, 35)),
            trim(substr($line, 95, 30)),
        );
    }

    public function getUsers()
    {
        $csv_file_handler = fopen($this->csv_file, "r");
        $users = array();
        while ($data = fgetcsv($csv_file_handler))
        {
            $user = new stdClass();
            $user->email = $data[0];
            $user->lastname = $data[1];
            $user->firstname = $data[2];
            $users[] = $user;
        }

        return $users;
    }

}

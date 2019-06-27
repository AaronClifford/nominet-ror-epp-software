<?php

class Domains
{

    public $domains;

    public function __construct()
    {
        return $this->data();
    }

    public function data()
    {

        if (file_exists("./domains/" . date("d-m-y") . ".txt")) {
            $file = file_get_contents("./domains/" . date("d-m-y") . ".txt");
            $this->domains = explode("\n", $file);
            $this->domains = array_map('trim', $this->domains);
        } else {
            echo "MESSAGE: No domain file found in the domains folder for today, please make sure there is a file with the following format. (" . date("d-m-y") . ".txt" . ")";
            exit();
        }

        return $this->domains;
    }

}
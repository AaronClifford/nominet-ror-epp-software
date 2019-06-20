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
        $file = file_get_contents("./domains/" . date("d-m-y") . ".txt");
        $this->domains = explode("\n", $file);
        $this->domains = array_map('trim', $this->domains);

        return $this->domains;
    }

}
<?php

namespace App\Nominet;

class Dac
{

    public function connect($address = "testbed-dac.nominet.org.uk", $port = 3043)
    {

        $this->connection = fsockopen($address, $port, $errno, $errstr);

        return $this->connection;
    }

    public function checkDomain($domain)
    {

        fwrite($this->connection, "{$domain}\r\n");

        $response = fgets($this->connection, 128);

        return explode(",", $response);
    }

}
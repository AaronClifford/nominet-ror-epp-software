<?php

class Dac
{

    private $connection;

    // Connect to the DAC
    public function connect($address = "testbed-dac.nominet.org.uk", $port = 3043)
    {
        $this->connection = fsockopen($address, $port, $errno, $errstr);

        return $this->connection;
    }

    // DAC query
    public function checkDomain($domain)
    {
        fwrite($this->connection, "{$domain}\r\n");

        $response = fgets($this->connection, 128);

        return explode(",", $response);
    }

    // Domain list processing, check if domain is registered or not.
    public function checkDomains($domains, $tag, $host, $port,$loop)
    {
        $connection = $this->connect($host, $port);

        $checkedDomains = array();

        // loop through domains to check if the name is registered by your tag, another tag, or if its available for the next round.
        foreach ($domains as &$domain) {

            fputs($connection, "$domain\r\n");
            $resp = fgets($connection, 102);

            $domainInfo = explode(",", trim($resp));

            if ($domainInfo[1] == "T" OR $domainInfo[1] == "N") {
                echo "MESSAGE: Domain: {$domainInfo[0]} (Available for next minute) \n";
                $checkedDomains[] = $domainInfo[0];
            } elseif (isset($domainInfo[5])) {
                if ($domainInfo[5] == $tag) {
                    if ($loop < 1) { $msg = "Already Registered"; } else { $msg = "Registered";}
                    echo "MESSAGE: Domain: {$domainInfo[0]} ({$msg}) ({$domainInfo[5]})\n";
                } else {
                    if ($loop < 1) { $msg = "Already Registered"; } else { $msg = "Missed";}
                    echo "MESSAGE: Domain: {$domainInfo[0]} ({$msg}) ({$domainInfo[5]}) \n";
                }
            }
        }

        echo "---------------------------------------------------------------------------\n";

        return $checkedDomains;
    }

}

<?php
// Set correct time zone
date_default_timezone_set('Europe/London');

require 'app/bootstrap.php';

$config = new Config();
$dac = new Dac();
$epp = new Epp();
$domains = new Domains();

// setup config and domain list
$config = (object)$config->data();
$domains = (array)$domains->domains;

// Set debug mode (php errors, edit in config).
if (!$config->settings["debug"]) {
    error_reporting(0);
}

// Connect to EPP
$epp->connect($config->epp["host"], $config->epp["port"]);

// Check EPP Connection
if (!$epp->login($config->epp["tag"], $config->epp["password"])) {
    exit("Error logging into EPP, please check your config and Nominet settings.");
}
echo "MESSAGE: EPP Connected\r\n";

$loop = 0;
$min = null;

while (1) {

    if ($loop > 0) {
        // Check domain list
        $domains = $dac->checkDomains($domains, $config->epp["tag"], $config->dac["host"], $config->dac["port"], $loop);
    }
    
    // Exit if domain list empty.
    if (empty($domains)) {
        echo "MESSAGE: Domain List Empty, Exiting\r\n";
        exit();
    }

    // Get the next minute, if on the 59th minute set to 0
    if ($loop == 0) {
       $min = intval(date("i") + 1);
    }
    if ($min > 59) {
        $min = 0;
    }

// Start the domain create process
    $epp->multiCreate($domains, $config->epp["password"], $config->epp["liveRegistrantID"], $min,$config->epp["create_requests"]);

    $loop++;
    $min++;
}

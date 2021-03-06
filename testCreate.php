<?php
// Set correct time zone
date_default_timezone_set('Europe/London');
require 'app/bootstrap.php';

$config = new Config();
$epp = new Epp();

// setup config
$config = (object)$config->data();

// Debug mode (
if (!$config->settings["debug"]) {
    error_reporting(0);
}

/* Connect to EPP */
$epp->connect($config->epp["host"], $config->epp["port"]);

/* Check EPP Connection */
if (!$epp->login($config->epp["tag"], $config->epp["password"])) {
    exit("Error logging into EPP, please check your config and Nominet settings.");
}
echo "MESSAGE: EPP Connected\r\n";

/* Create Test Contact */
if (!file_exists("REGISTRANT")) {
    if (!$epp->createContact($config->epp["testRegistrantID"], $config->epp["testRegistrantName"], $config->epp["testRegistrantOrg"])) {
        echo "MESSAGE:Error creating contact, please review the latest log file.\r\n";
        unlink("REGISTRANT");
        exit();
    }

    echo "MESSAGE: Contact Created Successfully\r\n";

    if (!$epp->createDomain($config->epp["testDomain"], $config->epp["password"], $config->epp["testRegistrantID"])) {
        echo "Error creating domain name, please review the lastest log file.";
        unlink("REGISTRANT");
        exit();
    }

    echo "MESSAGE: Domain {$config->epp["testDomain"]} Created Successfully\r\n";

} else {
    echo "MESSAGE: Exiting, nothing to do.";
}

<?php
return array(

    'dac' =>
        array('host' => 'testbed-dac.nominet.org.uk', 'port' => '3043'),

    'epp' =>
        array(
            'host' => 'ssl://testbed-epp.nominet.org.uk',
            'port' => '700',
            'tag' => '',
            'password' => '',
            'liveRegistrantID' => '',
            'testRegistrantID' => '', // Max 16 characters, change this every time you run the script.
            'testRegistrantName' => '',
            'testRegistrantOrg' => '',
            'testDomain' => '', // Must be a domain on the ROR list to successfully complete the Nominet requirements
            'create_requests' => 6), // Only tested on a low amount of queries, modification will be required for anymore.

    'settings' =>
        array('debug' => true)

);
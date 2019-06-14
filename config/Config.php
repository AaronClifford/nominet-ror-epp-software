<?php
return array(
    'dac' =>
        array('host' => 'testbed-dac.nominet.org.uk', 'port' => '3043'),

    'epp' =>
        array('host' => 'ssl://testbed-epp.nominet.org.uk',
            'port' => '700',
            'tag' => 'YOURTAG',
            'password' => 'YOURPASSWORD',
            'testRegistrantID' => '', // Max 16 characters, change this every time you run the script.
            'testRegistrantName' => '',
            'testRegistrantOrg' => '',
            'testDomain' => ''), // Must be a domain on the ROR list to successfully complete the Nominet requirements

    'settings' =>
        array('debug' => false)

);

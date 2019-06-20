<?php
return array(

    'dac' =>
        array('host' => 'testbed-dac.nominet.org.uk', 'port' => '3043'),

    'epp' =>
        array(
            'host' => 'ssl://testbed-epp.nominet.org.uk',
            'port' => '700',
            'tag' => 'ADOMAINS',
            'password' => 'Silversurfer123',
            'liveRegistrantID' => 'avvaaa-aaa-123',
            'testRegistrantID' => 'afssavvfafa123', // Max 16 characters, change this every time you run the script.
            'testRegistrantName' => 'Aaron Clifford',
            'testRegistrantOrg' => 'Aaron Clifford',
            'testDomain' => 'cvbaaaaacsf.uk', // Must be a domain on the ROR list to successfully complete the Nominet requirements
            'create_requests' => 6), // not functional, code changes needed to support more than six requests.

    'settings' =>
        array('debug' => true)

);

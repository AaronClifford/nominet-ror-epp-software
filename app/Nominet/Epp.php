<?php

namespace App\Nominet;

class Epp
{

    private $connection;

    public function connect($address = "testbed-epp.nominet.org.uk", $port = 700)
    {
        $timeout = @ini_get('default_socket_timeout');
        $flags = null;
        $options = null;
        $context = stream_context_create($options);
        $flags = STREAM_CLIENT_CONNECT;

        $this->connection = stream_socket_client($address . ':' . $port, $errno, $errstr, $timeout, $flags, $context);

        stream_set_blocking($this->connection, (int)false);
        stream_set_write_buffer($this->connection, 0);

        $this->readEPP($this->connection);
    }

    public function login($tag, $password)
    {
        $loginXML = '<?xml version="1.0" encoding="UTF-8"?>
  <epp xmlns="urn:ietf:params:xml:ns:epp-1.0"
       xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
       xsi:schemaLocation="urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd">
    <command>
      <login>
        <clID>' . $tag . '</clID>
        <pw>' . $password . '</pw>
        <options>
          <version>1.0</version>
          <lang>en</lang>
        </options>
        <svcs>
           <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
           <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
           <objURI>urn:ietf:params:xml:ns:host-1.0</objURI>
           <svcExtension>
             <extURI>http://www.nominet.org.uk/epp/xml/contact-nom-ext-1.0</extURI>
             <extURI>http://www.nominet.org.uk/epp/xml/domain-nom-ext-1.0</extURI>
           </svcExtension>
        </svcs>
      </login>
    </command>
  </epp>';

        return $this->sendEPP($loginXML, "Command completed successfully");
    }

    function createContact($registrant, $name, $org)
    {
        $createContactXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<epp xmlns=\"urn:ietf:params:xml:ns:epp-1.0\"
    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
    xsi:schemaLocation=\"urn:ietf:params:xml:ns:epp-1.0
    epp-1.0.xsd\">
    <command>
        <create>
            <contact:create
                xmlns:contact=\"urn:ietf:params:xml:ns:contact-1.0\" 
                xsi:schemaLocation=\"urn:ietf:params:xml:ns:contact-1.0
                contact-1.0.xsd\">
                <contact:id>" . $registrant . "</contact:id>
                <contact:postalInfo type=\"loc\">
                    <contact:name>" . $name . "</contact:name>
                    <contact:org>" . $org . "</contact:org>
                    <contact:addr>
                        <contact:street>Teststreet 1</contact:street>
                        <contact:street>Teststreet 2</contact:street>
                        <contact:city>Oxford</contact:city>
                        <contact:sp>England</contact:sp>
                        <contact:pc>OX1 1AH</contact:pc>
                        <contact:cc>GB</contact:cc>
                    </contact:addr>
                </contact:postalInfo>
                <contact:voice>+44.753035251</contact:voice>
                <contact:email>test@test.com</contact:email>
                <contact:authInfo>
                    <contact:pw></contact:pw>
                </contact:authInfo>
            </contact:create>
        </create>
    </command>
</epp>";

        return $this->sendEPP($createContactXML, "Command completed successfully", 1);
    }

    function createDomain($domain, $password)
    {
        $registrant = file_get_contents('REGISTRANT');

        $createDomainXML = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<epp xmlns=\"urn:ietf:params:xml:ns:epp-1.0\"
   xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
   xsi:schemaLocation=\"urn:ietf:params:xml:ns:epp-1.0
   epp-1.0.xsd\">
   <command>
     <create>
       <domain:create
         xmlns:domain=\"urn:ietf:params:xml:ns:domain-1.0\"
         xsi:schemaLocation=\"urn:ietf:params:xml:ns:domain-1.0
         domain-1.0.xsd\">
         <domain:name>" . $domain . "</domain:name>
         <domain:registrant>" . $registrant . "</domain:registrant>
         <domain:authInfo>
           <domain:pw>$password</domain:pw>
         </domain:authInfo>
       </domain:create>
     </create>
     <clTRID>abcde12345</clTRID>
   </command>
</epp>";

        return $this->sendEPP($createDomainXML, "Command completed successfully");
    }

    public function sendEPP($data, $find, $log = null)
    {
        fputs($this->connection, $data, strlen($data));

        $response = $this->readEPP($this->connection);

        if ($log) {
            $this->createLock($response);
        }

        $pos = strpos($response, $find);

        if ($pos !== false) {
            return true;
        }

        return false;
    }

    public function readEPP($connection)
    {
        $buffer = null;
        while (!feof($connection)) {
            $buffer .= stream_get_contents($connection, -1);
            if (strpos($buffer, "</epp>") !== false) {
                break;
            }
        }

        $this->logs($buffer, "EPP");

        return $buffer;
    }

    public function createLock($response)
    {
        $handle = fopen("REGISTRANT", 'w') or die('Cannot open file');
        $data = $this->getDataValue($response, "<contact:id>", "</contact:id>");
        fwrite($handle, $data);
        fclose($handle);
    }

    public function getDataValue($str, $from, $to)
    {
        $sub = substr($str, strpos($str, $from) + strlen($from), strlen($str));
        return substr($sub, 0, strpos($sub, $to));
    }

    public function logs($msg, $file)
    {
        $date = date("d-m-y");
        $time = $this->udate('H:i:s.u');
        
        $f = fopen("logs/" . $date . "-" . $file . ".txt", 'a');

        fputs($f, trim($time) . ",{$msg}\n");

        fclose($f);
    }

    public function udate($format, $utimestamp = null)
    {
        if (is_null($utimestamp))
            $utimestamp = microtime(true);
        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);
        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }


}

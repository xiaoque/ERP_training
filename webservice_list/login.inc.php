<?php

/**
 * Webservice in PHP with OpenERP 7.0 using Pear XML-RPC.
 * Example with with the Ideas module.
 * Tested on Debian server with Apache2, PHP5 and PEAR php-xml-rpc 1.5.3
 *
 * Author: Guillaume RIVIERE (C) 2013-2014
 *
 * With the help of:
 *  http://doc.openerp.com/v6.1/developer/12_api.html
 *  http://pear.php.net/package/XML_RPC/docs
 *  http://pear.php.net/manual/en/package.webservices.xml-rpc.api.php
 */

require_once('XML/RPC.php'); // Include PEAR library for XML-RPC

function login ($host, $port, $db, $user, $pass, $debug) {
  $client = new XML_RPC_Client('/xmlrpc/common', 'http://'.$host.':'.$port);
  $client->setDebug($debug);

  $msg = new XML_RPC_Message('login');
  $msg->addParam(new XML_RPC_Value($db, 'string'));
  $msg->addParam(new XML_RPC_Value($user, 'string'));
  $msg->addParam(new XML_RPC_Value($pass, 'string'));

  $resp = $client->send($msg);

  if (!$resp)
    die('Communication error: ' . $client->errstr);
  else if ($resp->faultCode()) { // FaultCode seams to be never set by OpenERP 7.0
    echo 'Fault Code: '.$resp->faultCode()."\r\n";
    echo 'Fault Reason: '.$resp->faultString()."\r\n";
    exit(1);
  }

  $val = $resp->value();
  
  if ($val->kindOf() == 'struct') {
    $struct = XML_RPC_decode($val) ;
    if (isset($struct['faultCode'])) { // Trick to catch fault code
      echo '<p>FAILURE RESPONSE: '.$struct['faultCode']."</p>\r\n" ;
      return false ;
    }
    return false ;
  }
  else { // kindOf == 'scalar'
    // Show id
    $uid = $val->scalarval();
    echo "Logged in $db as $user (uid:$uid)<br/>";
    return $uid ;
  }
}

?>

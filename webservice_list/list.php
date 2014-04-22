<?

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
require_once('login.inc.php');
require_once('vars.inc.php');

echo '<html>';
echo '<head><title>Test OpenERP 7.0 webservices</title>';
echo '<style>
table, th, td
{
border-collapse:collapse;
border:1px solid black;
}
th, td
{
padding:5px;
}
</style></head>';
echo '<body>';
echo '<h1>Test OpenERP 7.0 webservices</h1>';

// ================================================
// Login

echo '<h2>Login</h2>';

$uid = login ($HOST, $PORT, $DB, $USER, $PASS, $DEBUG) ;

// ================================================
// Create

echo '<h2>List</h2>';


$client = new XML_RPC_Client('/xmlrpc/object', "http://$HOST:$PORT");
$client->setDebug($DEBUG);

$structVal = array( // Values to give to the fields of the new record
  new XML_RPC_Value('immatriculation', 'string'),
  new XML_RPC_Value('km_in','int'),
  new XML_RPC_Value('price','float'),
);

$searchKey = array(// search constraints
  new XML_RPC_Value('energy','string'),
  new XML_RPC_Value('=','string'),
  new XML_RPC_Value('Diesel','string'),
);

//define a function to process the ids of cars
function getCarData($ids, $readClient){
  $readMsg = new XML_RPC_Message('execute');
  $readMsg->addParam(new XML_RPC_Value($DB, 'string'));
  $readMsg->addParam(new XML_RPC_Value($uid, 'int'));
  $readMsg->addParam(new XML_RPC_Value($PASS, 'string'));
  $readMsg->addParam(new XML_RPC_Value('secondhandcars.car', 'string')); // Name of the relation
  $readMsg->addParam(new XML_RPC_Value('read', 'string'));      // The ORM method
  $readMsg->addParam(new XML_RPC_Value($ids, 'int'));         // List of id of each record to read
  $readMsg->addParam(new XML_RPC_Value($structVal, 'array'));  

  $readResp = $readClient->send($readMsg);

  if (!$readResp)
    die('Communication error: ' . $readClient->errstr);
  else if ($readResp->faultCode()) { // FaultCode seams to be never set by OpenERP 7.0
    echo 'Fault Code: '.$readResp->faultCode()."\r\n";
    echo 'Fault Reason: '.$readResp->faultString()."\r\n";
    exit(1);
  }

  $readVal = $readResp->value() ;

  if ($readVal->kindOf() == 'struct') {
    $readStruct = XML_RPC_decode($readVal) ;
    if (isset($readStruct['faultCode'])) // Trick to catch fault code
      echo '<p>FAILURE RESPONSE: '.$readStruct['faultCode']."</p>\r\n" ;
  }
  else { // kindOf == 'array'
    $carInstance = XML_RPC_decode($readVal) ;
    // Show each record
    echo '<table><tr><th>Immatriculation</th><th>km_in</th><th>Price</th></tr>';
    foreach ($carInstance as $ai) {
      // Show values (according to selected fields) of the record
      echo '<tr><td>'.$ai["immatriculation"].'</td>';
      echo '<td>'.$ai["km_in"].'</td>';
      echo '<td>'.$ai["price"].'</td></tr>';
    }
    echo '</table>';
  }
}


// search to get all ids of cars that use Diesel as energy
$msg = new XML_RPC_Message('execute');
$msg->addParam(new XML_RPC_Value($DB, 'string'));
$msg->addParam(new XML_RPC_Value($uid, 'int'));
$msg->addParam(new XML_RPC_Value($PASS, 'string'));
$msg->addParam(new XML_RPC_Value('secondhandcars.car', 'string')); // Name of the relation
$msg->addParam(new XML_RPC_Value('search', 'string'));        // The ORM method
$msg->addParam(new XML_RPC_Value($searchKey, 'array'));      // Values of the new record

$resp = $client->send($msg);

if (!$resp)
  die('Communication error: ' . $client->errstr);
else if ($resp->faultCode()) { // FaultCode seams to be never set by OpenERP 7.0
  echo 'Fault Code: '.$resp->faultCode()."\r\n";
  echo 'Fault Reason: '.$resp->faultString()."\r\n";
  exit(1);
}

$val = $resp->value() ;

if ($val->kindOf() == 'struct') {
  $struct = XML_RPC_decode($val) ;
  if (isset($struct['faultCode'])) // Trick to catch fault code
    echo '<p>FAILURE RESPONSE: '.$struct['faultCode']."</p>\r\n" ;
}
else { // kindOf == 'array'
  $a = XML_RPC_decode($val) ;
  echo '<p>Number of results: '.count($a).'</p>';
  // call function to get each car's information
  getCarData($a, $client);
}

echo '</body>';
echo '</html>';

?>

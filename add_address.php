<?php

require('/opt/mabl/lib/routeros_api.class.php');
$lines = file(dirname(__FILE__)."/addresses.txt");

$API = new RouterosAPI();

$API->debug = false;

if ($API->connect('mikrotik_ip', 'username', 'password')) {

foreach ($lines as $line_num => $line) {

$line = str_replace("\n",'',$line);

$API->comm("/ip/firewall/address-list/add", array (
"address" => $line,
"list" => "blacklist",
"comment" => "autoblacklist",));

}

   $API->disconnect();

}

?>

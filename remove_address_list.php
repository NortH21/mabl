<?php
require('/opt/mabl/lib/routeros_api.class.php');

$API = new RouterosAPI();

$API->debug = false;

if ($API->connect('mikrotik_ip', 'username', 'password')) {

$API->write('/ip/firewall/address-list/print', false);
$API->write('?comment=autoblacklist', false);
$API->write('=.proplist=.id');
$ARRAYS = $API->read();

foreach ($ARRAYS as $id){

$API->write('/ip/firewall/address-list/remove', false);
$API->write('=.id=' . $id['.id']);
$READ = $API->read();

}

   $API->disconnect();

}

?>

<?php

require('/opt/mabl/lib/routeros_api.class.php');

$real_block_ip = array();
$timestamp = strtotime(date("Y-m-d",strtotime("-30 days")));

$options = array(
    'http'   => array(
    'method' => "GET",
    'header' => "Accept-language: en\r\n"
    ."User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n"
    )
);

$context   = stream_context_create($options);
$block_ips = file_get_contents("http://frod.subnets.ru/?get=csv&type=ip&time=".$timestamp, false, $context);

if ($block_ips !== ""){
    $block_ips_array = explode(PHP_EOL, $block_ips);

        foreach ($block_ips_array as $ip){
        list($one, $two, $three, ) = explode(".", $ip);
        $block_ip = $one.".".$two.".".$three.".0/24";

        if (!in_array($block_ip, $real_block_ip))
            $real_block_ip[]=$block_ip;
    }

    $block_list = implode(PHP_EOL, $real_block_ip);
    file_put_contents(dirname(__FILE__)."/ip.txt", $block_list);
}

$lines = file(dirname(__FILE__)."/ip.txt");

$API = new RouterosAPI();
$API->debug = false;

if ($API->connect('mikrotik_ip', 'username', 'passwd')) {

    $API->write('/ip/firewall/address-list/print', false);
    $API->write('?comment=autoblacklist', false);
    $API->write('=.proplist=.id');
    $ARRAYS = $API->read();

    foreach ($ARRAYS as $id){
        $API->write('/ip/firewall/address-list/remove', false);
        $API->write('=.id=' . $id['.id']);
        $READ = $API->read();
    }

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

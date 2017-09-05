<?php

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

if ($block_ips !== "")
{
    $block_ips_array = explode(PHP_EOL, $block_ips);

    foreach ($block_ips_array as $ip)
    {
         list($one, $two, $three, ) = explode(".", $ip);
         $block_ip = $one.".".$two.".".$three.".0/24";

         if (!in_array($block_ip, $real_block_ip))
              $real_block_ip[]=$block_ip;
    }

    $block_list = implode(PHP_EOL, $real_block_ip);
    file_put_contents(dirname(__FILE__)."/addresses.txt", $block_list);
}

?>

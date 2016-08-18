<?php
include "../library/Php2php.php";

/**
* You must always use '0.0.0.0' as the ip of the server
*/
$socket=new Php2php('0.0.0.0','3222');

$socket->on('tweet',function($client,$params) {
	$client->emit('tweeted',"Last tweet was today");	
});

$socket->on('blog',function($client,$params) {
	$client->emit('blogged',"Last blog was yesterday");	
});


$socket->run();
?>
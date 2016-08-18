<?php
/**
* This example shows how to connec to the socket server, send a command with parameter, and read the response
*/
include "library/Php2php.php";

$socket=new Php2php('127.0.0.1','3222');

//this method is called as the callback if a response of blogged is gotten from the server after the first call
$socket->on('blogged',function($client,$response) {
	$client->stdout("We have blogged successfully - $response");

	//this will reopen connection to the server, to emit this to the server - this is the second call
	$client->emit('tweet',"i love tweeting");	
});

//this is a response to the second call, triggered by the server sending back the tweeted command response
$socket->on('tweeted',function($client,$response) {
	$client->stdout("We have tweeted successfully - $response");
});

/**
* send message to server - this is the first call 
* an event handler on the server responds to this by sending blogged command as response, check server/server.php 
*/
$socket->emit('blog',"i love blogging");

$socket->run();


?>
<?php
/**
* This example shows how to connec to the socket server, send a command with parameter, and read the response
*/
include "library/Php2php.php";

$socket=new Php2php('127.0.0.1','3222');

$socket->emit('tweet',"i love tweeting");

echo $socket->run();


?>
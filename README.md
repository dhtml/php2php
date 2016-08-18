## Introduction
PHP2PHP is a library written in PHP that allows real-time communication between a client and server via web sockets.
However, contrary to websocket and socket.io, this is designed to communicate between 2 php scripts. 
A PHP script acting as the server, and another php script running as the client. 
This is specifically designed for long-running processes, queueing of tasks etc

It's created and developed by Anthony Ogundipe, CEO of [DHTMLExtreme](http://www.africoders.com).

## Features
* Ultra-fast communication between client and server.
* Simple to implement and configure especially into existing libraries.
* It can be integrated fairly easily with existing libraries.
* It can be used with mobile application development e.g. an android/ios client


## Quick Start
* Download the [zip master](https://github.com/dhtml/php2php/archive/master.zip)
* Extract the zip master into your web directory
* Open the examples folder to check out the basic and advanced functionalities.


Simple Usage:

```
You need to start the server from the commandline and not from inside the browser.

$: php server/Php2php.php

From linux, you may want to use: nohup php server/Php2php.php 

The server is meant to be running continuously so that it waits for connection, if you close the server, then the functionality will stop.

After you have successfully started the server, you can run the clients by launching blog.php or tweet.php (from the browser or via cli)

```


### Author
**Anthony Ogundipe** a.k.a dhtml

Special thanks to <a href="https://www.facebook.com/wasconet">Adewale Wilson</a> (wasconet) for his contributions to this library.

## Community
You can chat with us on facebook http://facebook.com/dhtml5 


## License
`php2php`'s code in this repo uses the MIT license, see our `LICENSE` file.

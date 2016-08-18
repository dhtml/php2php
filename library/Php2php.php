<?php 
/**
*  P H P 2 P H P 
* 
*  a PHP2PHP WebSocket API
* 
* This library enables a php socket client to connect to a php socket server in an OOP way 
* 
*  For more informations: {@link https://github.com/dhtml/php2php}
*  
*  @author Anthony Ogundipe
*  @e-mail: diltony@yahoo.com
*  @copyright Copyright (c) 18/8/2016 Anthony Ogundipe
*  @license http://opensource.org/licenses/mit-license.php The MIT License
*  @package php2php
*/
class Php2php {
	protected $maxBufferSize;        
	protected $master;
	protected $sockets                             = array();
	protected $users                                = array();
	protected $heldMessages                         = array();
	public $commands=Array();
	public $reconnect=false;
	protected $interactive                          = true;
	public $message="";

	/**
	* class constructor
	* @param	string		$addr			The ip address of the server
	* @param	string		$port		The port number to use for the service
	*/
	function __construct($addr,$port, $bufferLength = 1024000) {
		//determine if we are working in cli mode
		define('cli',$addr == '0.0.0.0' ? true:false);	
		
		$this->addr=$addr;
		$this->port=$port;
		$this->bufferLength=$bufferLength;
	}
	
	/**
	* send message between server and client
	* @param	string		$command			The command to send e.g. tweet
	* @param	string		$params				The command parameters
	* 
	* @return object
	*/
	public function emit($command,$params=null) {
		$this->message = json_encode(array('command'=>$command,'params'=>$params));

		//since client connection is closed, we re-open at this point
		if(!cli && $this->reconnect) {
			$this->start_client();
		}
		
		return $this;
	}

	/**
	* Run request whether client or server
	* 
	* @return when running on client, returns the json response if any
	*/
	public function run() {
		if(cli) {return $this->start_server();}
		else {return $this->start_client();}
	}

	/**
	* starts the client request, and ends it as well
	* returns the json response if any
	*/
	public function start_client() {
		$sock=socket_create(AF_INET,SOCK_STREAM,0) or die("-1");
		@socket_connect($sock,$this->addr,$this->port) or die("-1");
		@socket_write($sock,$this->message);

		$response=@socket_read($sock,$this->bufferLength);
		socket_close($sock);
		
		
		$input=json_decode($response);
		$this->onCommand($input->command,$input->params,true);

		return $response;
	}

	/**
	* Starts the server daemon component which runs endlessly
	*/
	public function start_server() {
		$this->master =socket_create(AF_INET,SOCK_STREAM,SOL_TCP) or die("Cannot create a socket");
		socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) or die("Failed: socket_option()");
		
		socket_bind($this->master,$this->addr,$this->port) or die("Could not bind to socket");
		socket_listen($this->master) or die("Could not listen to socket");
		$this->sockets['m'] = $this->master;
		$this->stdout("Server started\nListening on: $this->addr:$this->port\nMaster socket: ".$this->master);

		while(true) {
			/* Accept incoming  requests and handle them as child processes */
			$client =socket_accept($this->master) or die("Could not accept");

			// Read the input  from the client – 1024000 bytes
			$read =  socket_read($client, $this->bufferLength)  or die("Cannot read from socket");

			$this->stdout("Client connected. " . $client);
			
			$input=@json_decode($read);
			if(is_object($input)) {
				$response=$this->onCommand($input->command,$input->params);
			}
			
			socket_write($client,$this->message);
			socket_close($client);
		}

		socket_close($this->master);
	}

	/**
	* add events for both client and server
	*
	* @param	string		$command			The command to send e.g. tweet
	* @param	string		$params				The command parameters
	* 
	* @return null
	*/
	public function on($cmd,$cb) {
		$this->commands["$cmd"]=$cb;
		return $this;
	}
	
	/**
	* processes the events specified by client or server
	* @param	string		$cmd				The command to send e.g. tweet
	* @param	string		$params				The command parameters
	* @param	boolean		$reconnect			Sets the reconnect flag to true when running in client state
	* 
	* @return null
	*/
	public function onCommand($cmd,$params='',$reconnect=false) {
		$this->reconnect=$reconnect;
		
		$this->message=''; //blank message
		if(!isset($this->commands["$cmd"])) {return;}
		$this->commands["$cmd"]($this,$params);
	}

	

	
	/**
	* prints variables to the output buffer
	* @param	string		$message				The message to print
	* 
	* @return object
	*/
	public function stdout($message) {
		if ($this->interactive) {
			$message=is_string($message) ? $message : json_encode($message,JSON_PRETTY_PRINT);
			if(php_sapi_name() == "cli") {echo "$message\n";} else {echo "$message<br/>";}
		}
		
		return $this;
	}
	
	
}
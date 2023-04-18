<?php

// number of seconds a script is allowed to run, if set to zero, no time limit is imposed.
set_time_limit(0);

$host = "127.0.0.1";
$port = 10000;
$null = null;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Unable to create socket.\n");

socket_bind($socket, $host, $port) or die("Unable to bind socket.\n");
socket_listen($socket, 20) or die("Unable to set up socket listener\n");
socket_set_nonblock($socket);

$members     = []; // connection instances and names of connected members
$connections = []; // connection conection instances and main socket instance

$connections[] = $socket;

do {
    
    $reads = $connections;
    $writes = $exceptions = $null;

    // socket_select() modifies the reads[], so that it contains the connection instances that has sent something to the server at a particular time
    // if there are no messages, the array will be empty
    socket_select($reads, $writes, $exceptions, 0);

    // reads[] will contain $socket when there's a new connection request
    if (in_array($socket, $reads))
    {
        // socket_accept() creates a new Socket instance for the user, which may not be used to accept new connections
        $newConnection = socket_accept($socket);
    
        // // handshake
        // $headers = socket_read($newConnection, 1024);
        // handshake($headers, $newConnection, $host, $port);
    
        $connections[] = $newConnection;
    
        // // notify user by the reply
        // $reply = "Connected to the socket server.\n";
        // $reply = pack_data($reply);
        // socket_write($newConnection, $reply, strlen($reply));
    
        // when connection is established we don't need socket instance in the read[]
        $socketIndex = array_search($socket, $reads);
        unset($reads[$socketIndex]);
    }

    echo "server listen on port $port".PHP_EOL;
    var_dump($connections);
    sleep(2);

} while (1 == 1);

socket_close($socket);
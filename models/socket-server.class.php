<?php
//
//// unmask the data sent by client to the server
//function unmask($payload) {
//  // https://www.php.net/manual/en/language.operators.bitwise.php
//
//  // $a & $b (And) Bits that are set in both $a and $b are set.
//  // ( 0 = 0000) = ( 0 = 0000) & ( 5 = 0101)
//  // ( 1 = 0001) = ( 1 = 0001) & ( 5 = 0101)
//  // ( 0 = 0000) = ( 8 = 1000) & ( 5 = 0101)
//
//  // the ord() function returns the ASCII value of the first character of a string: ord("hello") = 104 / ord("h") = 104
//  $length = ord($payload[1]) & 127;
//
//  if ($length == 126)
//  {
//    // 0123 4567 890123...
//    // abcd efgh ijklmn...
//    //     |mask|data
//    $masks = substr($payload, 4, 4);
//    $data = substr($payload, 8);
//  }
//  else if ($length == 127)
//  {
//    // 0123456789 0123 4567...
//    // abcdefghij klmn opqr...
//    //           |mask|data
//    $masks = substr($payload, 10, 4);
//    $data = substr($payload, 14);
//  }
//  else
//  {
//    // 01 2345 67890123...
//    // ab cdef ghijklmn...
//    //   |mask|data
//    $masks = substr($payload, 2, 4);
//    $data = substr($payload, 6);
//  }
//
//  $text = '';
//
//  for ($i = 0; $i < strlen($data); ++$i)
//  {
//    // $a ^ $b (Xor) Bits that are set in $a or $b but not both are set.
//    // ( 5 = 0101) = ( 0 = 0000) ^ ( 5 = 0101)
//    // ( 4 = 0100) = ( 1 = 0001) ^ ( 5 = 0101)
//    // (13 = 1101) = ( 8 = 1000) ^ ( 5 = 0101)
//    $text .= $data[$i] ^ $masks[$i % 4];
//  }
//
//  return $text;
//}
//
//// prepares all necessary headers before sending data from the server to the client
//function pack_data($text) //encode($text)
//{
//  // https://www.php.net/manual/en/language.operators.bitwise.php
//  // $a | $b (Or) Bits that are set in either $a or $b are set.
//  // ( 5 = 0101) = ( 0 = 0000) | ( 5 = 0101)
//  // ( 7 = 0111) = ( 2 = 0010) | ( 5 = 0101)
//  // (13 = 1101) = ( 8 = 1000) | ( 5 = 0101)
//
//  // 0x1 text frame (FIN + opcode)
//  // dec: 128 | (1 & 15) = 129
//  // bin: 10000000 | (0001 & 1111) = 10000000 | 0001 = 10000001
//  $b1 = 0x80 | (0x1 & 0x0f);
//  $length = strlen($text);
//
//  // pack() function packs data into a binary string depending from the length
//  if ($length <= 125) $header = pack('CC', $b1, $length);
//  else if ($length > 125 && $length < 65536) $header = pack('CCS', $b1, 126, $length);
//  else if ($length >= 65536) $header = pack('CCN', $b1, 127, $length);
//
//  return $header.$text;
//}
//
//
//
//function handshake($requestHeaders, $clientSocket, $address, $port)
//{
//  if (preg_match("/Sec-WebSocket-Version: (.*)\r\n/", $requestHeaders, $match)) $version = $match[1];
//  else
//  {
//    print("The client doesn't support WebSocket");
//    return false;
//  }
//
//  if ($version == 13)
//  {
//    // Extract header variables
//    if (preg_match("/GET (.*) HTTP/", $requestHeaders, $match)) $root = $match[1];
//    if (preg_match("/Host: (.*)\r\n/", $requestHeaders, $match)) $host = $match[1];
//    if (preg_match("/Origin: (.*)\r\n/", $requestHeaders, $match)) $origin = $match[1];
//    if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $requestHeaders, $match)) $key = $match[1];
//
//    $acceptKey = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
//    // the sha1() function calculates the SHA-1 hash of a string: sha1($str) = f7ff9e8b7bb2e09b70935a5d785e0cc5d9d0abf0
//    $acceptKey = base64_encode(sha1($acceptKey, true));
//
//    $responseHeaders = "HTTP/1.1 101 Switching Protocols\r\n".
//                       "Upgrade: websocket\r\n".
//                       "Connection: Upgrade\r\n".
//                       "Sec-WebSocket-Accept: $acceptKey".
//                       "\r\n\r\n";
//
//    socket_write($clientSocket, $responseHeaders, strlen($responseHeaders));
//    return true;
//  }
//  else
//  {
//    print("WebSocket version 13 required (the client supports version {$version})");
//    return false;
//  }
//}
//
//
//// number of seconds a script is allowed to run, if set to zero, no time limit is imposed.
//set_time_limit(0);
//
//$host = "127.0.0.1";
//$port = 10000;
//$null = null;
//
//$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Unable to create socket.\n");
//socket_bind($socket, $host, $port) or die("Unable to bind socket.\n");
//socket_listen($socket, 20) or die("Unable to set up socket listener\n");
//
//$members = []; // connection instances and names of connected members
//$connections = []; // connection conection instances and main socket instance
//
//$connections[] = $socket;
//
//// check for connections and read messages from connected clients
//while (true)
//{
//  $reads = $connections;
//  $writes = $exceptions = $null;
//
//  // socket_select () modifies the reads[], so that it contains the connection instances that has sent something to the server at a particular time
//  // if there are no messages, the array will be empty
//  socket_select($reads, $writes, $exceptions, 0);
//
//  // reads[] will contain $socket when there's a new connection request
//  if (in_array($socket, $reads))
//  {
//    // socket_accept() creates a new Socket instance for the user, which may not be used to accept new connections
//    $newConnection = socket_accept($socket);
//
//    // handshake
//    $headers = socket_read($newConnection, 1024);
//    handshake($headers, $newConnection, $host, $port);
//
//    $connections[] = $newConnection;
//
//    // notify user by the reply
//    $reply = "Connected to the socket server.\n";
//    $reply = pack_data($reply);
//    socket_write($newConnection, $reply, strlen($reply));
//
//    // when connection is established we don't need socket instance in the read[]
//    $socketIndex = array_search($socket, $reads);
//    unset($reads[$socketIndex]);
//  }
//
//  // reads[] contain the sockets that have sent a message at a particular time
//  foreach ($reads as $key => $socketInstance)
//  {
//    // socket_read() returns a zero length string ("") when there is no more data to read
//    // data can be a text message or disconnection request e.g. when client closes the browser window
//    $data = socket_read($socketInstance, 1024);
//
//    if (!empty($data))
//    {
//      // if data is not empty tere is text message from the client
//      $message = unmask($data);
//      $maskedMessage = pack_data($message);
//
//      // write to all connected clients
//      foreach ($connections as $conKey => $conSocketInstance)
//      {
//        // first element of connections[] is not a client, it's the server instance itself, so we need to skip the first element
//        if ($conKey === 0) continue;
//        socket_write($conSocketInstance, $maskedMessage, strlen($maskedMessage));
//      }
//    }
//    else if ($data === "")
//    {
//      // if data is empty there is close connection request
//      echo "Disconnecting client $key.\n";
//      unset($connections[$key]);
//      socket_close($socketInstance);
//    }
//  }
//}
//
//socket_close($socket);
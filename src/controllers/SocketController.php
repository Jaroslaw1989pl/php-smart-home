<?php

declare(strict_types = 1);

namespace src\controllers;

use app\Request;
use app\Response;
use app\Session;


class SocketController extends _Controller
{
    private string $socketServerPath;

    public function __construct()
    {
        parent::__construct();

        $this->socketServerPath = \ROOT_DIR."/storage/socket-server-".$this->data['user']['uuid'].".php";
    }

    // #[Post('/socket/start')]
    public function start()
    {
        // create socket server if not exists
        if (file_exists($this->socketServerPath) === false)
        {
            $socketServerFile = fopen($this->socketServerPath, "w");
            fwrite($socketServerFile, SOCKET_SERVER);
            fclose($socketServerFile);
            chmod($this->socketServerPath, 0777);
        }
        // start socket server if not runs
        if (!exec("pidof php ".$this->socketServerPath))
        {
            $descriptorspec = [
                0 => ["pipe", "r"],
                1 => ["pipe", "w"],
                2 => ["file", \ROOT_DIR."/storage/error-output.txt", "a"]
            ];
            $process = proc_open("php $this->socketServerPath > /dev/null &", $descriptorspec, $pipe);
        }
    }

    // #[Post('/socket/stop')]
    public function stop()
    {
        // kill process if active
        if ($pid = intval(exec("pidof php ".$this->socketServerPath)))
            echo json_encode(exec("kill -9 $pid"));
    }
}


const SOCKET_SERVER = <<<TEMPLATE
<?php

\$host = "127.0.0.1";
\$port = 1989;

set_time_limit(0); //No timeout

\$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Creating socket server failed.");
socket_bind(\$socket, \$host, \$port) or die("Binding socket server failed.");
socket_listen(\$socket, 3) or die("Listening failed.");

//ustawienie gniazda nieblokującego wykonywanie operacji w przypadku niepowodzenia
socket_set_nonblock(\$socket);

do {
    \$accept = socket_accept(\$socket); //or die("Połączenie przychodzące od klienta nie powiodło się");
    //\$message = socket_read(\$accept, 1024); //or die("Odczytanie wiadomości od klienta nie powiodło się");

    @socket_getpeername(\$accept, \$client_address, \$client_port);
    @socket_getpeername(\$socket, \$server_address, \$server_port);
    
    if(@socket_read(\$accept, 1024))
    {
        echo "dane klienta: ".\$client_address." ".\$client_port."\\n";
        echo "dane serwera: ".\$server_address." ".\$server_port."\\n";
        //echo "Wiadomość od klienta: ".socket_read(\$accept, 1024)."\\n";
    }


    @socket_write(\$accept, "serwer nasłuchuje", strlen("serwer nasłuchuje"));
    //or die("Wysłanie wiadomości do klienta nie powiodło się");

    echo "serwer nasłuchuje\\n";
    usleep(1000000);

} while (1 == 1);
TEMPLATE;
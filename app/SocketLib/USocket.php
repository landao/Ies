<?php

namespace App\SocketLib;

class USocket
{

    /* const SERVER_IP = '10.10.33.22';

    const SERVER_PORT = '1234';*/

    public function sendCmd($command)
    {

        $out = array();

        $server = env('IOT_SERVER', '10.10.33.22');
        $port   = env('IOT_PORT', '1234');

        $command = $command . PHP_EOL;

        if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            $out[] = "socket_create() failed: reason: " . socket_strerror(socket_last_error());
        } else {

            if (!@socket_connect($socket, $server, $port)) {
                $out[] = "socket_connect() failed. Reason: " . socket_strerror(socket_last_error($socket));
            } else {

                $out[] = "Sending data...<br>";
                socket_write($socket, $command, strlen($command));

                $out[] = "Reading response:<br>";
                while ($msg = socket_read($socket, 2048)) {
                    $out[] = $msg;
                }
            }
            socket_close($socket);
        }

        return $out;
    }

    public function gatewaySendCmd($command, $server, $port)
    {

        $out = array();

        $command = $command . PHP_EOL;

        if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            $out[] = "socket_create() failed: reason: " . socket_strerror(socket_last_error());
        } else {

            if (!@socket_connect($socket, $server, $port)) {
                $out[] = "socket_connect() failed. Reason: " . socket_strerror(socket_last_error($socket));
            } else {

                $out[] = "Sending data...<br>";
                socket_write($socket, $command, strlen($command));

                $out[] = "Reading response:<br>";
                // while ($msg = socket_read($socket, 2048)) {
                //     $out[] = $msg;
                // }
            }
            socket_close($socket);
        }

        return $out;
    }

    public function execPyCode($filename)
    {
        if (file_exists($filename)) {
            $cmd            = 'sudo -S su landao';
            $descriptorspec = array(
                array('pipe', 'r'),
                array('pipe', 'w'),
                array('pipe', 'w'),
            );
            $pipes   = array();
            $process = proc_open($cmd, $descriptorspec, $pipes);
            $out ;

            //fwrite($pipes[0], 'cd /home/landao/Documents/pycode' . PHP_EOL);

            fwrite($pipes[0], 'python ' . $filename . PHP_EOL);
           
            fclose($pipes[0]);
            $out = array(stream_get_contents($pipes[1]), stream_get_contents($pipes[2]));

            return $out;

        } else {
            return -1;
        }

    }

}

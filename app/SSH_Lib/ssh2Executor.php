<?php

namespace App\SSH_Lib;

class ssh2Executor
{
    public $conn = array();

    public function __construct($hostIp, $sshPort, $user, $pass)
    {
        $this->host_ip    = $hostIp;
        $this->ssh_port   = $sshPort;
        $this->login_user = $user;
        $this->password   = $pass;
    }

    public function connectServer()
    {
        if (is_array($this->conn) && isset($this->conn['status']) && $this->conn['status'] == true && isset($this->conn['link'])) {
            return $this->conn;
        } else {
            $sshp       = $this->ssh_port;
            $connection = ssh2_connect($this->host_ip, $sshp);
            if (!$connection) {
                throw new Exception("fail: unable to establish connection\nPlease IP or if server is on and connected");
            }
            $pass_success = ssh2_auth_password($connection, $this->login_user, $this->password);
            if (!$pass_success) {
                throw new Exception("fail: unable to establish connection\nPlease Check your password");
            }
            $this->conn['status'] = true;
            $this->conn['link']   = $connection;
            return $this->conn;
        }

    }

    public function ssh2handle()
    {
        /**
         * Estamblish SSH connection,
         * put site under maintenance,
         * pull chamges from git,
         * install composer dependencies,
         * execute migrations,
         * put site online
         */
        // $remote = config('pckg.framework.' . DeployProject::class . '.remotes.default');
        // $path = $remote['root'];
        // $commands = ['cd ' . $path => 'Changing root directory', 'php ' . $path . 'console project:down' => 'Putting project offline', 'php ' . $path . 'console project:pull' => 'Executing project:pull', 'php ' . $path . 'console migrator:install' => 'Installing migrations', 'php ' . $path . 'console project:up' => 'Putting project up', 'php ' . $path . 'console cache:clear' => 'Clearing cache'];
        // $this->output('Estamblishing SSH connection.');
        // $sshConnection = ssh2_connect($remote['host'], $remote['port']);
        // $this->output('SSH connection estamblished.');

        //  // Authenticate.

        // if (!ssh2_auth_password($sshConnection, $remote['username'], $remote['password'])) {
        //     throw new Exception('Cannot estamblish SSH connection to remote');
        // }
        // foreach ($commands as $command => $notice) {
        //     $this->output($notice);
        //     $stream = ssh2_exec($sshConnection, $command);
        //     $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        //     stream_set_blocking($errorStream, true);
        //     stream_set_blocking($stream, true);
        //     $errorStreamContent = stream_get_contents($errorStream);
        //     $streamContent = stream_get_contents($stream);
        //     $this->output($errorStreamContent . "\n" . $streamContent);
        // }
        // $this->output('Done!');
    }

/**
 * @param Queue $queue
 */
    public function handleQueue(Queue $queueService)
    {
        // $waitingQueue = $queueService->getWaiting();
        // /**
        //  * Set queue as started, we'll execute it later.
        //  */
        // $waitingQueue->each(function (QueueRecord $queue) {
        //     $this->output('#' . $queue->id . ': ' . 'started (' . date('Y-m-d H:i:s') . ')');
        //     $queue->changeStatus('started');
        // }, false);
        // /**
        //  * Execute jobs.
        //  */
        // $waitingQueue->each(function (QueueRecord $queue) {
        //     $this->output('#' . $queue->id . ': ' . 'running (' . date('Y-m-d H:i:s') . ')');
        //     $queue->changeStatus('running');
        //     $this->output('#' . $queue->id . ': ' . $queue->command);
        //     $output = null;
        //     $sha1Id = sha1($queue->id);
        //     try {
        //         $timeout  = strtotime($queue->execute_at) - time();
        //         $command  = $queue->command . ' && echo ' . $sha1Id;
        //         $lastLine = null;
        //         if (false && $timeout > 0) {
        //             exec('timeout -k 60 ' . $timeout . ' ' . $command, $output);
        //         } else {
        //             if (strpos($command, 'furs:')) {
        //                 $command    = str_replace(['/www/schtr4jh/derive.foobar.si/htdocs/', '/www/schtr4jh/beta.derive.foobar.si/htdocs/'], '/www/schtr4jh/bob.pckg.derive/htdocs/', $command);
        //                 $connection = ssh2_connect(config('furs.sship'), 22);
        //                 ssh2_auth_password($connection, config('furs.sshuser'), config('furs.sshpass'));
        //                 $stream      = ssh2_exec($connection, $command);
        //                 $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        //                 stream_set_blocking($errorStream, true);
        //                 stream_set_blocking($stream, true);
        //                 $errorStreamContent = stream_get_contents($errorStream);
        //                 $streamContent      = stream_get_contents($stream);
        //                 $output             = $errorStreamContent . "\n" . $streamContent;
        //                 $lastLine           = substr($streamContent, -41, 40);
        //             } else {
        //                 exec($command, $output);
        //                 $lastLine = end($output);
        //             }
        //         }
        //         if ($lastLine != $sha1Id) {
        //             $queue->changeStatus('failed_permanently', ['log' => 'FAILED: ' . (is_string($output) ? $output : implode("\n", $output))]);
        //             return;
        //             throw new Exception('Job failed');
        //         }
        //     } catch (Throwable $e) {
        //         $queue->changeStatus('failed_permanently', ['log' => exception($e)]);
        //         return;
        //     }
        //     if (!$output) {
        //         $queue->changeStatus('failed_permanently', ['log' => 'No output']);
        //         return;
        //     }
        //     $this->output('#' . $queue->id . ': ' . 'finished (' . date('Y-m-d H:i:s') . ')');
        //     $queue->changeStatus('finished', ['log' => is_string($output) ? $output : implode("\n", $output)]);
        // }, false);
    }

    public function sshiRun($cmds)
    {
        $connection = $this->connectServer();
        if ($connection['status'] == true) {
            $output = array();
            foreach ($cmds as $cmd) {
                $stream = ssh2_exec($connection['link'], $cmd);
                stream_set_blocking($stream, true);
                $stream_out  = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO);
                $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
                stream_set_blocking($errorStream, true);
                $output[]             = stream_get_contents($stream_out);
                $errorStreamContent[] = stream_get_contents($errorStream);
            }
            return $output;
        }
        return array();

    }

    public function sshiDeviceDiscover($cmd, $timeLimit = 30)
    {
        $connection = $this->connectServer();
        if ($connection['status'] == true) {
            $output = array();
            $stdout = ssh2_exec($connection['link'], $cmd);
            $stderr = ssh2_fetch_stream($stdout, SSH2_STREAM_STDERR);

            if (!empty($stdout)) {

                $t0      = time();
                $err_buf = null;
                $out_buf = null;

                // Try for 30s
                do {

                    $err_buf .= fread($stderr, 4096);
                    $out_buf .= fread($stdout, 4096);

                    $done = 0;
                    if (feof($stderr)) {
                        $done++;
                    }
                    if (feof($stdout)) {
                        $done++;
                    }

                    $t1   = time();
                    $span = $t1 - $t0;

                    // Wait here so we don't hammer in this loop
                    sleep(1);

                } while (($span < $timeLimit) && ($done < 2));

                $output[] = $out_buf;

            } else {
                echo "Failed to Shell\n";
            }

            return $output;
        }
        return array();

    }

    /*public function get_list($myhost, $usern, $passw, $mypath, &$data)
    {
    if (!function_exists("ssh2_connect")) {
    die("function ssh2_connect doesn't exist");
    }
    if (!($conn = ssh2_connect($myhost, 22))) {
    echo "fail: unable to establish connection\n";
    } else {
    if (!ssh2_auth_password($conn, $usern, $passw)) {
    echo "fail: unable to authenticate\n";
    } else {
    if (!($stream = ssh2_exec($conn, "ls -1 " . $mypath))) {
    echo "fail: unable to execute command\n";
    } else {
    stream_set_blocking($stream, true);
    // allow command to finish
    $data = "";
    while ($buf = fread($stream, 4096)) {
    $data .= $buf;
    }
    fclose($stream);
    }
    }
    }
    ssh2_exec($conn, 'exit');
    }
     */
    public function close()
    {
        $conn = $this->connectServer();
        if (is_array($conn) && isset($conn['status'])) {
            if (ssh2_exec($conn['link'], 'exit') === false) {
                return false;
            }
            unset($conn);
            return true;
        }
        return false;
    }

}

<?php

namespace App\SSH_Lib;

class ssh2ExecCmd
{
    public function __construct($hostName, $hostIp, $sshPort, $user, $pass)
    {
        $this->host_name  = $hostName;
        $this->host_ip    = $hostIp;
        $this->ssh_port   = $sshPort;
        $this->login_user = $user;
        $this->password   = $pass;
    }

    public function connect_server()
    {
        $link = ssh2_connect($this->host_ip, $this->ssh_port);
        if (!$link) {
            $res['result'] = false;
            $res['detail'] = "[Error] Failed to connect to the server. Check the connection information or the status of the server and network." . $this->host_ip;
        } else {
            $login = ssh2_auth_password($link, $this->login_user, $this->password);
            if (!$login) {
                $res['result'] = false;
                $res['detail'] = "[Error] Failed to connect to the server. Check the connection information or the status of the server and network." . $this->host_ip;
            } else {
                $res['result'] = true;
                $res['detail'] = $link;
            }
        }
        return $res;
    }

    public function checkuser_on_ssh($user_name)
    {
        $command_list[0] = array('command' => "finger \"" . $user_name . "\"" . PHP_EOL, 'usleep' => 500000);
        $res             = $this->exec_shellscript($command_list);
        if (preg_match('/Login: ' . $user_name . '/', $res['detail']) == 1) {
            // exist
            $result = 'Registered';
        } elseif (preg_match('/' . $user_name . ': no such user\\./', $res['detail']) == 1) {
            // not exist
            $result = 'Not registered';
        } else {
            // cannot exec
            $result = 'Failed';
        }
        return $result;
    }

    public function registuser_on_ssh($user_info)
    {
        $command_list[0] = array('command' => "sudo /usr/local/bin/adduser_chroot3.pl \"" . $user_info['accountname'] . "\" \"" . $user_info['password'] . "\" \"" . $user_info['familyname'] . "\" \"" . $user_info['givenname'] . "\" \"" . $user_info['officename_roman'] . "\" \"" . $user_info['officetell_num'] . "\" \"" . $user_info['job_type'] . "\"" . PHP_EOL, 'usleep' => 7000000);
        $res             = $this->exec_shellscript($command_list);

        if (preg_match('/RESULT:Failed\\./', $res['detail']) == 1) {
            $res['result'] = false;
            $res['detail'] = '[Error] User Name: ' . $user_info['accountname'] . ' Failed to register user ';
        } elseif (preg_match('/RESULT:Exists\\./', $res['detail']) == 1) {
            $res['result'] = false;
            $res['detail'] = '[Error] User Name: ' . $user_info['accountname'] . ' It is a registered user';
        } elseif (preg_match('/RESULT:Success\\./', $res['detail']) == 1) {
            $res['result'] = true;
            $res['detail'] = '';
        } else {
            $res['result'] = false;
            $res['detail'] = '[Alert] User Name: ' . $user_info['accountname'] . ' Failed to register user ';
        }
        return $res;
    }

    public function exec_shellscript(array $command_list)
    {
        $res = $this->connect_server();
        if ($res['result']) {
            $link = $res['detail'];
        } else {
            return $res;
        }

        $stream = ssh2_shell($link, "xterm", null, 80, 24, SSH2_TERM_UNIT_CHARS);
        // usleep(1000000);
        for ($i = 0; $i < count($command_list); $i++) {
            fwrite($stream, $command_list[$i]['command' . PHP_EOL]);
            usleep($command_list[$i]['usleep']);

            if ($i == count($command_list) - 1) {
                $screen = "";
                while ($line = fgets($stream)) {
                    ob_flush();
                    flush();
                    $screen .= $line . PHP_EOL;
                }
                fclose($stream);
            }
        }
        $res['detail'] = $screen;
        return $res;
    }

    public function exec(array $command_list)
    {
        $res = $this->connect_server();
        if ($res['result']) {
            $link = $res['detail'];
        } else {
            return $res;
        }

        if (!($stream = ssh2_shell($link, "xterm", null, 80, 24, SSH2_TERM_UNIT_CHARS))) {
            $res['detail'] = "fail: unable to establish shell\n";

        } else {
            for ($i = 0; $i < count($command_list); $i++) {
                fwrite($stream, $command_list[$i]['command'] . PHP_EOL);
                usleep($command_list[$i]['usleep']);

                if ($i == count($command_list) - 1) {
                    $screen = "";
                    while ($line = fread($stream, 4096)) {
                        flush();
                        $screen .= $line . PHP_EOL;
                    }
                    fclose($stream);
                }
            }
        }

        $res['detail'] = $screen;
        return $res;
    }

}

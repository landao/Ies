<?php

namespace App\SSH_Lib;

use App\SSH_Lib\ssh2ExecCmd;
use App\Utilities\PyUtilities;
use phpseclib\Net\SSH2;

class PyParkCmd
{

    protected $masterUser;
    protected $masterPass;

    protected $slaveUser;
    protected $slavePass;

    public function __construct()
    {
        $this->masterUser = env('MASTER_USER', 'pi');
        $this->masterPass = env('MASTER_PASS', '12345678');
        $this->slaveUser  = env('SLAVE_USER', 'pi');
        $this->slavePass  = env('SLAVE_PASS', '12345678');
    }

    protected $connect_result = array();

    protected $sshConnect = array();

    protected $cmdCollection = array();

    protected function connectServer($ip, $user, $password)
    {
        if (is_array($this->sshConnect) == true && array_key_exists($ip, $this->sshConnect) == true && array_key_exists('conn', $this->sshConnect[$ip]) == true) {
            if ($this->sshConnect[$ip]['conn'] === true) {
                return $this->sshConnect[$ip]['link'];
            }

        } else {
            $linkssh                       = new ssh2ExecCmd($ip, $ip, 22, $user, $password);
            $this->sshConnect[$ip]         = array();
            $this->sshConnect[$ip]['conn'] = true;
            $this->sshConnect[$ip]['link'] = $linkssh;            
            return $linkssh;
        }

    }

    public function getsshCollection()
    {
        return $this->sshConnect;
    }

    public function mount($ip, $user, $password, $natIp, $natUser, $natPass)
    {
        $console = array();

        $linkssh = $this->connectServer($ip, $user, $password);

        $commandList = array();

        $cmd = env('CMD_NAS_MOUNT', '');
        $cmd = str_replace('{1}', $natUser, $cmd);
        $cmd = str_replace('{2}', $natPass, $cmd);
        $cmd = str_replace('{3}', $natIp, $cmd);

        $this->cmdCollection[] = $cmd;

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[] = $res['detail'];

        return $console;

    }

    public function unmount($ip, $user, $password)
    {
        $console = array();

        $linkssh = $this->connectServer($ip, $user, $password);

        $commandList = array();

        $cmd = env('CMD_NAS_UNMOUNT', '');

        //$cmd = escapeshellcmd($cmd);

        $this->cmdCollection[] = $cmd;

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[] = $res['detail'];

        return $console;

    }

    public function startMaster($ip, $user, $password)
    {

        $console     = array();
        $linkssh     = $this->connectServer($ip, $user, $password);
        $commandList = array();

        $cmd = env('CMD_MASTER_START', '');
        $cmd = str_replace('{1}', $ip, $cmd);

        //$cmd = escapeshellcmd($cmd);

        $this->cmdCollection[] = $cmd;

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[]                             = $res['detail'];
        $this->connect_result[$ip . "_master"] = 1;
        return $console;

    }

    public function stopMaster($ip, $user, $password)
    {

        $console     = array();
        $linkssh     = $this->connectServer($ip, $user, $password);
        $commandList = array();

        $cmd = env('CMD_MASTER_STOP', '');
        //$cmd = escapeshellcmd($cmd);
        $this->cmdCollection[] = $cmd;

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[]                                  = $res['detail'];
        $this->connect_result[$ip . "_master_stop"] = 1;
        return $console;

    }

    public function startSlave($ip, $user, $password, $masterIp)
    {
        $console     = array();
        $linkssh     = $this->connectServer($ip, $user, $password);
        $commandList = array();

        $cmd = env('CMD_SLAVE_START', '');
        $cmd = str_replace('{1}', $masterIp, $cmd);

        //$cmd = escapeshellcmd($cmd);
        $this->cmdCollection[] = $cmd;

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[]                            = $res['detail'];
        $this->connect_result[$ip . "_slave"] = 1;
        return $console;
    }

    public function stopSlave($ip, $user, $password)
    {
        $console     = array();
        $linkssh     = $this->connectServer($ip, $user, $password);
        $commandList = array();

        $cmd = env('CMD_SLAVE_STOP', '');

        //$cmd = escapeshellcmd($cmd);
        $this->cmdCollection[] = $cmd;

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[]                                 = $res['detail'];
        $this->connect_result[$ip . "_slave_stop"] = 1;
        return $console;
    }

    public function clusterSetup($setupReq)
    {
        if (is_array($setupReq) == true && count($setupReq) > 0) {
            if (is_array($setupReq['nas']) && $setupReq['clusterMaster'] != '') {
                $nasIp                = $setupReq['nas']['ip'];
                $nasUser              = $setupReq['nas']['user'];
                $nasPass              = $setupReq['nas']['pass'];
                $cmd                  = array();
                $slaveCmd             = array();
                $masterCmd            = array();
                $cmd['clusterMaster'] = $setupReq['clusterMaster'];
                if ($nasIp != '' && $nasUser != '' && $nasPass != '') {
                    foreach ($setupReq as $key => $value) {
                        if ($key != 'nas') {
                            if (PyUtilities::contain('start_slave_', $key)) {
                                $ip                       = str_replace('start_slave_', '', $key);
                                $ip                       = str_replace('_', '.', $ip);
                                $cmd['mount'][]           = array('ip' => $ip, 'user' => env('SLAVE_USER', 'pi'), 'pass' => env('SLAVE_PASS', ''));
                                $slaveCmd['startSlave'][] = $ip;
                            }

                            if (PyUtilities::contain('stop_slave_', $key)) {
                                $ip = str_replace('stop_slave_', '', $key);
                                $ip = str_replace('_', '.', $ip);
                                //$cmd[] = array('unmount' => $ip) ;
                                $slaveCmd['stopSlave'][] = $ip;
                            }

                            if (PyUtilities::contain('start_master_', $key)) {
                                $ip                         = str_replace('start_master_', '', $key);
                                $ip                         = str_replace('_', '.', $ip);
                                $cmd['mount'][]             = array('ip' => $ip, 'user' => env('MASTER_USER', 'pi'), 'pass' => env('MASTER_PASS', ''));
                                $masterCmd['startMaster'][] = $ip;
                            }

                            if (PyUtilities::contain('stop_master_', $key)) {
                                $ip = str_replace('stop_master_', '', $key);
                                $ip = str_replace('_', '.', $ip);

                                $this->clusterStop($setupReq['ExistsCluster'], $ip);
                            }

                        }
                    }
                }

                if (is_array($masterCmd) && count($masterCmd) > 0) {
                    foreach ($masterCmd as $key => $value) {
                        foreach ($value as $k => $v) {
                            $cmd[$key][] = $v;
                        }
                    }
                }

                if (is_array($slaveCmd) && count($slaveCmd) > 0) {
                    foreach ($slaveCmd as $key => $value) {
                        foreach ($value as $k => $v) {
                            $cmd[$key][] = $v;
                        }
                    }
                }

                $this->clusterRunCmd($cmd, $nasIp, $nasUser, $nasPass);

            }
            return $cmd;
        }

        return array();

    }

    protected function clusterRunCmd($cmd, $nasIp, $nasUser, $nasPass)
    {
        if (is_array($cmd) && $nasIp != '' && $nasUser != '' && $nasPass != '') {
            foreach ($cmd as $key => $value) {
                if ($key === 'mount') {
                    foreach ($value as $k => $v) {
                        $this->mount($v['ip'], $v['user'], $v['pass'], $nasIp, $nasUser, $nasPass);
                    }

                }
                if ($key === 'startMaster') {
                    foreach ($value as $k => $v) {
                        $this->startMaster($v, $this->masterUser, $this->masterPass);
                    }
                }
                if ($key === 'startSlave') {
                    foreach ($value as $k => $v) {
                        $this->startSlave($v, $this->slaveUser, $this->slavePass, $cmd['clusterMaster']);
                    }
                    //
                }
                if ($key === 'stopMaster') {
                    foreach ($value as $k => $v) {
                        $this->stopMaster($v, $this->masterUser, $this->masterPass);
                    }

                }
                if ($key === 'stopSlave') {
                    foreach ($value as $k => $v) {
                        $this->stopSlave($v, $this->slaveUser, $this->slavePass);
                    }
                }

            }
        }
    }

    protected function clusterStop($clusterData, $masterIp)
    {
        if (isset($clusterData->url) && $clusterData->url != '') {
            if (PyUtilities::createSparkUrl($masterIp) === $clusterData->url) {

                if (isset($clusterData->workers) && is_array($clusterData->workers) && count($clusterData->workers) > 0) {
                    foreach ($clusterData->workers as $wk) {
                        if ( isset($wk['host']) && $wk['host'] != '' && $wk['state'] === 'ALIVE' ) {
                            $this->stopSlave($wk['host'], $this->slaveUser, $this->slavePass);
                            usleep(1000000);
                        }
                    }
                }
                $this->stopMaster($masterIp, $this->masterUser, $this->masterPass);
                usleep(1000000);
                $this->unmount($masterIp, $this->masterUser, $this->masterPass);
                usleep(1000000);
            }
        }
    }

    private function _mount($ip, $user, $password, $natIp, $natUser, $natPass)
    {
        $console = array();

        $linkssh = new ssh2ExecCmd($ip, $ip, 22, $user, $password);

        $commandList = array();

        $cmd = env('CMD_NAS_MOUNT', '');
        $cmd = str_replace('{1}', $natUser, $cmd);
        $cmd = str_replace('{2}', $natPass, $cmd);
        $cmd = str_replace('{3}', $natIp, $cmd);

        //$cmd = escapeshellcmd($cmd);

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[] = $res['detail'];

        return $console;

    }

    public function _mountTest($ip, $user, $password, $natIp, $natUser, $natPass)
    {
        $console = array();

        $linkssh = new ssh2ExecCmd($ip, $ip, 22, $user, $password);

        $commandList = array();

        $cmd = "echo $password | sudo -S mount -t cifs -o user=debian,pass=123456 //" . $natIp . "/ClusterDATA /home/landaovm/DATA";

        //$cmd = escapeshellcmd($cmd);

        $commandList[] = array('command' => $cmd, 'usleep' => 2000000);

        $res = $linkssh->exec($commandList);

        $console[] = $res['detail'];

        return $console;

    }

    public function _startMaster($ip, $user, $password)
    {

        $console = array();
        $ssh     = new SSH2($ip);
        echo 'Connecting to ' . $ip . "...";

        if (!$ssh->login($user, $password)) {
            exit('Login Failed');
            echo 'Login Failed';
            $console[]                             = 'Failed';
            $this->connect_result[$ip . "_master"] = 0;
        } else {
            $ssh->enablePTY();
            echo "Login Successfully. ";
            $ssh->setTimeout(1);
            $cmd = env('CMD_MASTER_START', '');
            $cmd = str_replace('{1}', $ip, $cmd);

            $ssh->write($cmd . " \n");
            $console[] = $ssh->read('/.*@.*[$|#]/', SSH2::READ_REGEX);
            $ssh->write("ls \n");
            $console[] = $ssh->read('/.*@.*[$|#]/', SSH2::READ_REGEX);

            $console[]                             = 'success';
            $this->connect_result[$ip . "_master"] = 1;
        }
        return $console;
    }

    public function _startSlave($ip, $user, $password, $masterIp)
    {

        $console = array();
        $ssh     = new SSH2($ip);
        echo 'Connecting to ' . $ip . "...";

        if (!$ssh->login($user, $password)) {
            exit('Login Failed');
            echo 'Login Failed';
            $console[]                            = 'Failed';
            $this->connect_result[$ip . "_slave"] = 0;
        } else {
            $ssh->enablePTY();
            echo "Login Successfully. ";

            $ssh->write("sudo ls" . "\n");
            $console[] = $ssh->read();

            $cmd = env('CMD_SLAVE_START', '');
            $cmd = str_replace('{1}', $masterIp, $cmd);

            $console[] = $cmd;

            $ssh->write($cmd . "\n");
            $console[] = $ssh->read('/.*@.*[$|#]/', SSH2::READ_REGEX);
            $ssh->write("ls \n");
            $console[]                            = $ssh->read('/.*@.*[$|#]/', SSH2::READ_REGEX);
            $console[]                            = 'success';
            $this->connect_result[$ip . "_slave"] = 1;
        }

        return $console;
    }

    public function getConnectionData()
    {
        return $this->connect_result;
    }

    public function getCmdCollection()
    {
        return $this->cmdCollection;
    }

}

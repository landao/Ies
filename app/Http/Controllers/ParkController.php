<?php
namespace App\Http\Controllers;

use App\ClusterSetup;
use App\Model\Spark\BatchDataConfigModel;
use App\Model\Spark\SensorConfigModel;
use App\Model\Spark\SensorTransformer;
use App\Model\Spark\SparkMasterInfo;
use App\SocketLib\USocket;
use App\SSH_Lib\PyParkCmd;
use App\SSH_Lib\ssh2Executor;
use App\StreamDataConfig;
use App\Utilities\PyUtilities;
use App\Utilities\StreamProcUtil;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Karriere\JsonDecoder\JsonDecoder;
use Session;
use Cookie;

class ParkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $data;        
        $jsonData;
        $masterinfo = new SparkMasterInfo;

        $rUrl     = env('SPARK_MASTER', '');
        $rUrlPort = env('SPARK_INFO_PORT');
        // if ($rUrl != '') {

        //     $rUrl .= ':' . $rUrlPort . '/json/';

        //     if (!@file_get_contents('http://' . $rUrl)) {
        //         $data = '';
        //     } else {
        //         $jsonData = file_get_contents('http://' . $rUrl);
        //         $data     = json_decode($jsonData, true);
        //     }

        // }

        $jsonData = $this->getClusterInfo($rUrl, 8080);
        if (strlen($jsonData) <= 0) {
            $jsonData = $this->getClusterInfo($rUrl, 8081);
        }
        if (strlen($jsonData) > 0) {
            $data = json_decode($jsonData, true);
        }

        if (strlen($jsonData) > 0) {
            $jsonDecoder = new JsonDecoder();
            $masterinfo  = $jsonDecoder->decode($jsonData, SparkMasterInfo::class);
        }

        $clusterNames = array();
        $masterIp     = $masterinfo->getMasterIp();
        // if ($masterIp != '') {
        //     $cluster                 = ClusterSetup::where('ip_address', $masterIp)->first();
        //     $clusterNames[$masterIp] = $cluster;
        // }

        return view('Park.index', compact('masterinfo', 'clusterNames', 'masterIp'));
    }

    public function getClusterInfo($server, $port)
    {
        $data;
        $jsonData;

        $rUrl     = $server;
        $rUrlPort = $port;

        if ($rUrl != '') {

            $rUrl .= ':' . $rUrlPort . '/json/';

            if (!@file_get_contents('http://' . $rUrl)) {
                $data = '';
            } else {
                $jsonData = file_get_contents('http://' . $rUrl);
                return $jsonData;
            }

        }
        return;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $data;
        $jsonData;
        $masterinfo = new SparkMasterInfo;

        $rUrl     = env('SPARK_MASTER', '');
        $rUrlPort = env('SPARK_INFO_PORT');
        /* if ($rUrl != '') {

        $rUrl .= ':' . $rUrlPort . '/json/';

        if (!@file_get_contents('http://' . $rUrl)) {
        $data = '';
        } else {
        $jsonData = file_get_contents('http://' . $rUrl);
        $data     = json_decode($jsonData, true);
        }

        }
         */

        $jsonData = $this->getClusterInfo($rUrl, 8080);
        if (strlen($jsonData) <= 0) {
            $jsonData = $this->getClusterInfo($rUrl, 8081);
        }
        if (strlen($jsonData) > 0) {
            $data = json_decode($jsonData, true);
        }

        if ( strlen($jsonData) > 0) {
            $jsonDecoder = new JsonDecoder();
            $masterinfo  = $jsonDecoder->decode($jsonData, SparkMasterInfo::class);
            Session::put('ClusterData', $masterinfo);
        }

        $ipList = array();
        if (Session::has('ipList')) {
            $ipList = Session::get('ipList');
        }

        $nasIp = PyUtilities::getNasIp($ipList);

        $clusterNames = array();
        $masterIp     = $masterinfo->getMasterIp();
        // if ($masterIp != '') {
        //     $cluster                 = ClusterSetup::where('ip_address', $masterIp)->first();
        //     $clusterNames[$masterIp] = $cluster;
        // }

        return view('Park.create', compact("ipList", "masterinfo", "clusterNames", 'nasIp', 'masterIp'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function _store(Request $request)
    {

        $out = array();

        ob_implicit_flush(true);
        $deviceDiscover = env('DeviceDiscoverApp', 'TestDeviceApp2507');

        // $exe_command = 'ping -c 4  google.com';
        $exe_command = "sudo ./" . $deviceDiscover;
        //$exe_command = 'whoami';

        $descriptorspec = array(
            0 => array("pipe", "r"), // stdin
            1 => array("pipe", "w"), // stdout -> we use this
            2 => array("pipe", "w"), // stderr
        );

        $process = proc_open($exe_command, $descriptorspec, $pipes);

        $console = "";

        set_time_limit(25);

        if (is_resource($process)) {

            while (!feof($pipes[1])) {
                $return_message = fgets($pipes[1], 1024);
                if (strlen($return_message) == 0) {
                    break;
                }

                $console .= $return_message;
                ob_flush();
                flush();
            }

            while (!feof($pipes[2])) {
                $return_error = fgets($pipes[2], 1024);
                if (strlen($return_error) == 0) {
                    break;
                }

                echo $return_error . '<br />';
                ob_flush();
                flush();
            }

        }

        $out = explode("\n", $console);
        return redirect()->route('pypark.create')->with('ipList', $out);
    }

    public function store(Request $request)
    {

        $out = array();

        $server = env('SPARK_MASTER');
        $user   = env('MASTER_USER');
        $pass   = env('MASTER_PASS');

        $deviceDiscover = env('DeviceDiscoverApp');

        // $exe_command = 'ping -c 4  google.com';
        $exe_command = "sudo " . $deviceDiscover;

        $ssh2 = new ssh2Executor($server, 22, $user, $pass);
        $ssh2->connectServer();
        //$console = $ssh2->sshiRun(array($exe_command));        
        $console = $ssh2->sshiDeviceDiscover($exe_command, 30);
        $ssh2->close();

        $out = explode("\n", $console[0]);
        return redirect()->route('pypark.create')->with('ipList', $out);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = 1)
    {

    }

    public function deploy(Request $req)
    {
        $input        = Input::all();
        $pyPark       = new PyParkCmd();
        $console      = array();
        $result       = array();
        $input_prefix = array('start_slave_', 'start_master_', 'stop_slave_', 'stop_master_');
        $nasIp        = $req->get('nasIp');
        $masterIp     = $req->get('clusterMaster');
        echo Session::get('ipList');

        $setupReq = array();
        if ($nasIp != '') {
            $nasUser         = $req->get('nas_user_' . str_replace(".", "_", $nasIp));
            $nasPass         = $req->get('nas_pass_' . str_replace(".", "_", $nasIp));
            $setupReq['nas'] = array('ip' => $nasIp, 'user' => $nasUser, 'pass' => $nasPass);
        } else {
            $out['error'] = "NAS cannot found. Setup cannot continue.";
            goto end;
        }
        if ($masterIp != '') {
            $setupReq['clusterMaster'] = $masterIp;
        } else {
            $out['error'] = "Master cannot found. Setup cannot continue.";
            goto end;
        }

        if (is_array($input) == true && count($input) > 0) {
            foreach ($input as $key => $value) {
                if (PyUtilities::inArray($input_prefix, $key) == true) {
                    $setupReq[$key] = $value;
                }
            }
        }
        if (Session::has('ClusterData')) {
            $setupReq['ExistsCluster'] = Session::get('ClusterData');
        }
        $out    = $pyPark->clusterSetup($setupReq);
        $result = $pyPark->getConnectionData();
        $out    = $pyPark->getCmdCollection();

        end:
        return view('Park.deploy', compact('console', 'result', 'out', 'masterIp'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function pushData()
    {

        $config = new StreamDataConfig;

        /*$formDataString = \App\Utilities\CookieMan::getCookie('streamConfig',false);
        if ($formDataString != '' && strlen($formDataString) > 0){
            $config = json_decode($formDataString);            
        }*/

        return view('Park.pushData', compact('config'));
    }

    public function gatewayLogConfig()
    {
        $config = new StreamDataConfig;

        return view('Park.gatewayLogConfig', compact('config'));

    }

    public function execGatewayConfig(Request $req)
    {

        $temp;
        $senList = array();

        $config          = new StreamDataConfig;
        $config->command = 3;

        $hosts              = $req->get('hostName');
        $ip_port            = StreamProcUtil::hostIpParse($hosts);
        $config->hosts      = $ip_port;
        $config->filterName = $req->get('configName');
        $logMode            = $req->get('logMode');

        if ($logMode === 'File') {
            $logData     = $req->get('fileLog');
            $config->log = StreamProcUtil::parseFileLogCond($logData);
        } else if ($logMode === 'Date') {
            $logData     = $req->get('dateLog');
            $config->log = StreamProcUtil::parseDateLogCond($logData);
        }

        $out = array();

        $cmd = $config->toJson();
        $cmd = PyUtilities::streamConfigtoJson($cmd);

        $out = array();

        /*if (is_array($config->hosts) == true && count($config->hosts) > 0) {
        $socket = new USocket;
        $server = env('GATEWAY_LOGGER_SERVER');
        $port   = env('GATEWAY_LOGGER_PORT');
        $cmd    = str_replace('-', '_', $cmd);
        $out[]  = $cmd;
        $out[]  = $socket->gatewaySendCmd($cmd, $server, $port);
        } else {
        $out[] = "Socket host and Port is invalid";
        }*/

        $socket = new USocket;
        $server = env('GATEWAY_LOGGER_SERVER');
        $port   = env('GATEWAY_LOGGER_PORT');
        $cmd    = str_replace('-', '_', $cmd);
        $out[]  = $cmd;
        $out[]  = $socket->gatewaySendCmd($cmd, $server, $port);

        return view('Park.streamConfigSend', compact('out'));

    }

    public function streamConfigSend(Request $req)
    {

        $temp;
        $senList = array();

        $config             = new StreamDataConfig;
        $config->command    = intval($req->get('command'));
        $config->hostName   = $req->get('hostName');
        $config->port       = intval($req->get('port'));
        $config->master     = env('STREAM_PROCESS_SERVER', '[Local*]');
        $config->batchTime  = intval($req->get('batchtime'));
        $config->savePath   = env('STREAM_PROCESS_SAVEPATH', '[default]');
        $config->filterName = $req->get('configName');

        $sensors = $req->get('sensorList');
        $filter  = array();
        if ($sensors != '') {
            $items = explode(',', $sensors);
            if (is_array($items) == true && count($items) > 0) {
                for ($i = 0; $i < count($items); $i++) {
                    $li       = $req->get('sensor_' . $items[$i]);
                    $li       = trim(preg_replace('/\s\s+/', ' ', $li));
                    $li       = str_replace('\\', '', $li);
                    $filter[] = '{' . $li . '}';

                    $jsonDecoder = new JsonDecoder();
                    $jsonDecoder->register(new SensorTransformer());
                    $temp      = $jsonDecoder->decode('{' . $li . '}', SensorConfigModel::class);
                    $senList[] = $temp;

                }
            }
        }

        $config->filter = $senList;

        $out = array();

        $cmd = $config->toJson();
        
        //\App\Utilities\CookieMan::setCookie('streamConfig',$cmd, 600);                        
                    
        $cmd = PyUtilities::streamConfigtoJson($cmd);

        $out   = array();
        $out[] = $cmd;

        $socket = new USocket;
        $out[]  = $socket->sendCmd($cmd);

        return view('Park.streamConfigSend', compact('out'));

    }

    public function getClusterMasterPage($masterIp)
    {

        $client     = new \GuzzleHttp\Client(['http_errors' => true]);
        $masterinfo = new SparkMasterInfo;
        $statusCode = -100;
        try {
            $res        = $client->request('GET', PyUtilities::createSparkMasterJson($masterIp));
            $statusCode = $res->getStatusCode();
            // 200
            $contentType = $res->getHeaderLine('content-type');
            $body        = $res->getBody();
            if ($body != '' && $statusCode == 200) {
                //$body = json_decode($body);
                $jsonDecoder = new JsonDecoder();
                $masterinfo  = $jsonDecoder->decode($body, SparkMasterInfo::class);
            }

        } catch (ClientException $e) {

        }

        if (!isset($masterinfo->url) || strlen($masterinfo->url) <= 0) {
            try {
                $res        = $client->request('GET', PyUtilities::createSparkMasterJson81($masterIp));
                $statusCode = $res->getStatusCode();
                // 200
                $contentType = $res->getHeaderLine('content-type');
                $body        = $res->getBody();
                if ($body != '' && $statusCode == 200) {
                    //$body = json_decode($body);
                    $jsonDecoder = new JsonDecoder();
                    $masterinfo  = $jsonDecoder->decode($body, SparkMasterInfo::class);
                }

            } catch (ClientException $e) {

            }
        }

        return view('Park.Partials.clusterMaster', compact('masterinfo', 'statusCode'));

    }

    public function batchProcessConfig()
    {
        $config = new BatchDataConfigModel;
        /*$formDataString = \App\Utilities\CookieMan::getCookie('batchConfig',false);
        if ($formDataString != '' && strlen($formDataString) > 0){
            $config = json_decode($formDataString);            
        }*/
        return view('Park.batchProcessConfig', compact('config'));
    }

    public function batchProcessSend(Request $req)
    {

        $temp;
        $senList = array();

        $config             = new BatchDataConfigModel;
        $config->command    = intval($req->get('command'));
        $config->master     = env('STREAM_PROCESS_SERVER', '[Local*]');
        $config->batchData  = $req->get('dataSource');
        $config->savePath   = $req->get('dataDestination');
        $config->filterName = $req->get('configName');

        $sensors = $req->get('sensorList');
        $filter  = array();
        if ($sensors != '') {
            $items = explode(',', $sensors);
            if (is_array($items) == true && count($items) > 0) {
                for ($i = 0; $i < count($items); $i++) {
                    $li       = $req->get('sensor_' . $items[$i]);
                    $li       = trim(preg_replace('/\s\s+/', ' ', $li));
                    $li       = str_replace('\\', '', $li);
                    $filter[] = '{' . $li . '}';

                    $jsonDecoder = new JsonDecoder();
                    $jsonDecoder->register(new SensorTransformer());
                    $temp      = $jsonDecoder->decode('{' . $li . '}', SensorConfigModel::class);
                    $senList[] = $temp;

                }
            }
        }

        $config->filter = $senList;

        $out = array();

        $cmd = $config->toJson();
        $cmd = PyUtilities::streamConfigtoJson($cmd);

        $out   = array();
        $out[] = $cmd;

        //\App\Utilities\CookieMan::setCookie('batchConfig',$cmd, 600);                        

        $socket = new USocket;
        $out[]  = $socket->sendCmd($cmd);

        return view('Park.batchProcessSend', compact('out'));

    }

    public function setupDataLogger()
    {
        return view('Park.setupDataLogger');
    }

    public function dataLoggerSetting()
    {
        return view('Park.dataLoggerSetting');
    }

}

<?php

namespace App\Http\Controllers;

use App\SocketLib\USocket;
use Illuminate\Http\Request;
use Session;

class PyCompController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("PyComp.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $out = array();
        if (Session::has('ipList')) {
            $out = Session::get('ipList');
        }
        return view('PyComp.runPyCode', compact('out'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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

    public function runPyCode(Request $req)
    {
        $code = $req->get('code');
        if (strlen($code) > 0) {
            $dir  = '/home/landao/Documents/pycode';
            $file = "tempPy.py";
            file_put_contents("$dir/$file", $code);
            $py  = new USocket;
            $out = $py->execPyCode("$dir/$file");
            return $out;
        }

        return;
    }

    public function ipython(Request $req)
    {

        $out = array();

        ob_implicit_flush(true);

        //$exe_command = 'ping -c 4  google.com';

        $exe_command = 'python /home/landao/Documents/pycode/tempPy.py';

        $descriptorspec = array(
            0 => array("pipe", "r"), // stdin
            1 => array("pipe", "w"), // stdout -> we use this
            2 => array("pipe", "w"), // stderr
        );

        $process = proc_open($exe_command, $descriptorspec, $pipes);

        $console = "";

        if (is_resource($process)) {

            while (!feof($pipes[1])) {
                $return_message = fgets($pipes[1]);
                if (strlen($return_message) == 0) {
                    break;
                }

                echo $return_message . "<br />";
                ob_flush();
                flush();
            }

            while (!feof($pipes[2])) {
                $return_error = fgets($pipes[2]);
                if (strlen($return_error) == 0) {
                    break;
                }

                echo $return_error . "<br />";
                ob_flush();
                flush();
            }

        }

        
        //return redirect()->route('pycomp.create')->with('ipList', $out);

    }

}

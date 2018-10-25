<?php

namespace App\Model\Spark;

use Illuminate\Database\Eloquent\Model;

class StreamLogFileModel
{

    public function __construct(){

    }    
    public $path_folder;
    public $file_name;
    public $log_type;
    public $maxFileNum;
    public $maxFileSize;
 
}
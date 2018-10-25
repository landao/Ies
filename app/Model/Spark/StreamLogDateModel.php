<?php

namespace App\Model\Spark;

use Illuminate\Database\Eloquent\Model;

class StreamLogDateModel
{

    public function __construct(){

    }    
    
    public $path_folder;
    public $file_name;
    public $log_type;
    public $maxDaysToRetain;
 
}
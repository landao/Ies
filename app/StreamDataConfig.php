<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StreamDataConfig extends Model
{
    
   protected $fillable = [
   	
        'command','hosts', 'port', 'master', 'batchTime', 'savePath', 'filterName', 'filter','log'
    ];


}



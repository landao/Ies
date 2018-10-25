<?php

namespace App\Model\Spark;

use Illuminate\Database\Eloquent\Model;

class BatchDataConfigModel extends Model
{
    
   protected $fillable = [
   	
        'command', 'batchData','master', 'savePath', 'filterName', 'filter','log'
    ];


}



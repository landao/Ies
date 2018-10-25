@extends('layouts.app')
@section('content')
<div class="row">
    <style type="text/css">
    .bg-title{
    
    }
    .text-left{
    text-align: left;
    }
    .td-icon{
    width: 60px;
    }
    </style>
    <div class="col-md-10 col-md-offset-1">
        <div class="ibox float-e-margins">
            <div class="ibox-title ibox-info" style="background-color:#2f4050;color:#ffffff"><h5>Setup Data Logger</h5>
                <div class="ibox-tools"> <span class="label label-warning-light pull-right">Virtium</span></div>
            </div>
            <div class="ibox-content">
                <div class="alert alert-info">
                    
                    <h4>
                        You can ask me to log data in the following way:
                    </h4>
                    
                    <ul>
                        <li>
                            <h4>Keep the last number of days or hours of the raw data. Delete the older data or archive them in cloud.</h4>
                        </li>
                        <li><h4>Compress the data during logging, and ship to cloud</h4></li>
                        <li><h4>Log data into the format you want (csv, MongoDB, Parquet, etc.)</h4></li>
                        <li><h4>I will be more useful as you tell me what skills I need to acquire </h4></li>
                    </ul>

                </div>
                <div class="project-list">
                    <table class="table table-hover">

                        <tr>
                            <td colspan="3" >
                                <strong><h4> <b> Select your combo </b> </h4></strong>
                            </td>                            
                        </tr>
                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <span class="fa-stack">
                                    <!-- The icon that will wrap the number -->
                                    <!-- <span class="fa fa-circle-o fa-stack-2x"></span> -->
                                    <!-- a strong element with the custom content, in this case a number -->
                                    <strong class="fa-stack-2x">
                                        1    
                                    </strong>
                                </span>                                    
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('/pypark/dataLoggerSetting') }}"><h4>Raw data, keep the last number of days, optional cloud</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>

                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <span class="fa-stack">
                                    <!-- The icon that will wrap the number -->
                                    <!-- <span class="fa fa-circle-o fa-stack-2x"></span> -->
                                    <!-- a strong element with the custom content, in this case a number -->
                                    <strong class="fa-stack-2x">
                                        2    
                                    </strong>
                                </span>                                    
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('/pypark/dataLoggerSetting') }}"><h4>Compressed data, keep the last number of days, optional cloud</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>

                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <span class="fa-stack">
                                    <!-- The icon that will wrap the number -->
                                    <!-- <span class="fa fa-circle-o fa-stack-2x"></span> -->
                                    <!-- a strong element with the custom content, in this case a number -->
                                    <strong class="fa-stack-2x">
                                        3    
                                    </strong>
                                </span>                                    
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('/pypark/dataLoggerSetting') }}"><h4>Log my data into a format (csv, MongoDB, Parquet,…)</h4> </a>                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>

                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <span class="fa-stack">
                                    <!-- The icon that will wrap the number -->
                                    <!-- <span class="fa fa-circle-o fa-stack-2x"></span> -->
                                    <!-- a strong element with the custom content, in this case a number -->
                                    <strong class="fa-stack-2x">
                                        4    
                                    </strong>
                                </span>                                    
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('/pypark/dataLoggerSetting') }}"><h4>Build your own combo (coming soon…)</h4> </a>                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
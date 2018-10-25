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
            <div class="ibox-title ibox-info" style="background-color:#2f4050;color:#ffffff"><h5>StorFly-IES</h5>
                <div class="ibox-tools"></div>
            </div>
            <div class="ibox-content">
                <div class="alert alert-info">
                    
                    <strong> <h4>Hi there, I am StorFly-IES. I am a storage. Though, I can do a little bit more than just storing data since I have been taught with some skills.</h4> </strong>
                    <br />
                    <h4>Some things you can ask me to do:</h4>
                </div>
                <div class="project-list">
                    <table class="table table-hover">
                        <!--  <tr>
                            <td class="project-status">
                                <span class="label label-primary">Active</span>
                            </td>
                            <td class="project-title">
                                <a href="project_detail.html">There are many variations of passages</a>
                                <br>
                                <small>Created 11.08.2014</small>
                            </td>
                            <td class="project-completion">
                                <small>Completion with: 28%</small>
                                <div class="progress progress-mini">
                                    <div style="width: 28%;" class="progress-bar"></div>
                                </div>
                            </td>
                            <td class="project-people">
                                <a href=""><img alt="image" class="img-circle" src="img/a7.jpg"></a>
                                <a href=""><img alt="image" class="img-circle" src="img/a6.jpg"></a>
                                <a href=""><img alt="image" class="img-circle" src="img/a3.jpg"></a>
                            </td>
                            <td class="project-actions">
                                <a href="#" class="btn btn-white btn-sm"><i class="fa fa-folder"></i> View </a>
                                <a href="#" class="btn btn-white btn-sm"><i class="fa fa-pencil"></i> Edit </a>
                            </td>
                            
                        </tr> -->
                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href="{{ url('/pypark/setupDataLogger') }}"><img alt="image" class="img-circle" src="{{ asset('images/64px/compose.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('/pypark/setupDataLogger') }}"><h4>I am a simple Data Logger with optional cloud connection</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>
                         
                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href="{{ url('/pypark/pushDataConfig') }}"><img alt="image" class="img-circle" src="{{ asset('images/64px/volume.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                               <a href="{{ url('/pypark/pushDataConfig')  }}"><h4> I can do simple Stream Data Processing </h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>
                         <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href=" {{ url('pypark/batchProcessConfig') }} "><img alt="image" class="img-circle" src="{{ asset('images/64px/magnifyingglass.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('pypark/batchProcessConfig') }}"><h4>I can do some Batch Data Processing</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href=""><img alt="image" class="img-circle" src="{{ asset('images/64px/clipboard.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                                <a href=""><h4>I am a NAS with optional cloud connection</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>
                       
                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href="{{ url('/pypark') }}" ><img alt="image" class="img-circle" src="{{ asset('images/64px/contacts.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                                <a href="{{ url('/pypark') }}" ><h4>Set up a cluster with my peers. I am more powerful that way.</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>

                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href=""><img alt="image" class="img-circle" src="{{ asset('images/64px/settings.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                                <a href=""><h4>My current setting</h4> </a>
                                
                            </td>
                            <td class="project-actions bg-title" >
                                <span><i class="fa fa-angle-right fa-4x"></i></span>
                            </td>
                        </tr>

                        <tr>
                            <td class="project-people bg-title text-left td-icon">
                                <a href=""><img alt="image" class="img-circle" src="{{ asset('images/64px/star.png') }}"></a>
                            </td>
                            <td class="project-title bg-title">
                                <a href=""><h4>How do I feel, today?</h4> </a>
                                
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
@extends('layouts.app')
@push('styles')
<link href="{{ asset('/css/plugins/nouslider/jquery.nouislider.css') }}" rel="stylesheet">
<link href="{{ asset('/css/plugins/ionRangeSlider/ion.rangeSlider.css') }}" rel="stylesheet">
<link href="{{ asset ('css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/codemirror/codemirror.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/codemirror/ambiance.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/switchery/switchery.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row" ng-app="GatewayConfigApp" ng-controller="GatewayConfigCtrl"  id="EmpTrainingCtrl">
  <style type="text/css">
  .bg-title{
    background-color:#2f4050;
    color: #ffffff;
  }
  .text-left{
    text-align: left !important;
  }
  .td-icon{
    width: 60px;
  }


   .div-disabled {
      display: none;
    }
</style>
<div class="col-md-8">
  <div class="ibox ">
    <div class="ibox-title bg-title">
      <h5>Gateway Data Logger Setting</h5> <span class="label label-primary"> </span>
      <div class="ibox-tools">
      </div>
    </div>
    <div class="ibox-content">
      <div>
        <form method="post" action="{{ action('ParkController@execGatewayConfig') }}" class="form-horizontal">
          @csrf
          <div class="form-group text-left"><label class="col-sm-6 control-label text-left">Setup how to get data from gateway logger</label>
            <div class="col-sm-6"></div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left"> Setting Name </label>
            <div class="col-sm-10">
              <input class="form-control" type="text" name="configName" value="Defaultname" required>
            </div>
          </div>

          <div class="form-group"><label class="col-sm-2 control-label text-left"> Gateway IP/Port List </label>
            <div class="col-sm-10">
              <input class="form-control" type="text" name="hostName" placeholder='10.10.10.11:1234' required>
              <span class="help-block m-b-none"> Example: 10.10.10.11:1234, 10.10.10.21:1111 </span>
            </div>

          </div>
         <!--  <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left">Listening Port</label>
            <div class="col-sm-10"><input class="form-control" type="text" name="port" required> <span class="help-block m-b-none"> Port for Stream Data ( 1000 - 99999).</span>
            </div>
          </div> -->
          
           <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left"> Logger Setting </label>
            <div class="col-sm-10">
              <div>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="radioFileLog" value="File" name="logMode" checked="" ng-model="streamingLogMode" ng-change="setLogMode('File',$event.target)">
                    <label for="inlineRadio1"> File Log </label>
                </div>
                <div class="radio radio-info radio-inline">
                    <input type="radio" id="radioDateLog" value="Date" name="logMode" ng-model="streamingLogMode" ng-change="setLogMode('Date',$event.target)">
                    <label for="inlineRadio2"> Date Log </label>
                </div>
              </div>
              <div style="padding-top:8px" ng-class="{ 'div-disabled': streamingLogMode == 'Date'}" >
                 <textarea class="sensor-config" name="fileLog">
"path_folder":"path",
"file_name":"filename",
"log_type":"file",
"maxFileNum":10,
"maxFileSize":10
                 </textarea>
              </div>
              <div style="padding-top:8px" ng-class="{'div-disabled': streamingLogMode == 'File'}">
                 <textarea class="sensor-config" name="dateLog">
"path_folder":"path",
"file_name":"filename",
"log_type":"date",
"maxDaysToRetain":10
                 </textarea>
              </div>
            </div>
          </div>
         
          <div class="hr-line-dashed"></div>
          <div class="form-group">
            <div class="col-sm-12 text-center">
              <div>
                <input type="hidden" name="sensorList" id='sensorList'>
                <input type="hidden" name="command" value="1">
                <button type="submit" class="btn btn-success" >Submit</button>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-md-4"></div>
              <div class="form-group col-md-4" style="margin-top:5px">
                <div class="se-pre-con loading-spinner" id="loader" style="padding-top: 20px; display: none;">
                  <div class="sk-spinner sk-spinner-wave">
                    <div class="sk-rect1"></div>
                    <div class="sk-rect2"></div>
                    <div class="sk-rect3"></div>
                    <div class="sk-rect4"></div>
                    <div class="sk-rect5"></div>
                  </div>
                  <div class="text-center" >
                    <strong>Setup in progress ...</strong>
                  </div>
                  
                </div>
              </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('/js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/plugins/codemirror/codemirror.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/plugins/codemirror/mode/javascript/javascript.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/angular/angular.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/customJS/streamDataConfig/ang.gateway.config.js?v=10111') }}" charset="utf-8"></script>
<script src="{{ asset('js/plugins/switchery/switchery.js') }}" charset="utf-8"></script>
<script type="text/javascript">
  $("#batchtime").ionRangeSlider({
    min: 0,
    max: 100,
    from: 30,
    postfix: "s",
    prettify: false,
    hasGrid: true
  });

  var cm = new Array();
  function setCodeEditor(){
    $(".sensor-config").each(function(index){
      var myCodeMirror = CodeMirror.fromTextArea($(this)[0] , {
        lineNumbers: true,
        mode:  "javascript"
      });
      myCodeMirror.setSize( 500, 120);
      cm.push(myCodeMirror);
    })
  }
  function clearCodeEditor(){
    if (cm != null && cm.length > 0){
      for (var i=0; i < cm.length; i++){
        cm[i].toTextArea();
      }
    }
  }
  jQuery(document).ready(function($) {
    setTimeout(function(){
      setCodeEditor();
    },500);
    var elem = document.querySelector('.js-switch');
    var switchery = new Switchery(elem, { color: '#1AB394', size: 'large' });

    $('form').on('submit',function(){
      setTimeout(function(){
      $("div.loading-spinner").css("display","block");
      }, 500);
    });


  });
</script>
@endpush

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
<div class="row" ng-app="GatewayConfigApp" ng-controller="GatewayConfigCtrl"  id="LoggerSettingCtrl">
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
      <h5>Data Logger Setting</h5> 
      <div class="ibox-tools">
      </div>
    </div>
    <div class="ibox-content">
      <div>
        <form method="post" action="{{ action('ParkController@execGatewayConfig') }}" class="form-horizontal">
          @csrf

           <script type="text/javascript">              
              window.onload = function () {                
                angular.element(document.getElementById('LoggerSettingCtrl')).scope().getCookieSetting("dataLoggerSetting");                              
              }
                               
            </script>

          <div class="form-group text-left"><label class="col-sm-6 control-label text-left"></label>
            <div class="col-sm-6"></div>
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
              <div style="padding-top:8px" ng-if=" input.fileLog == '' " ng-class="{ 'div-disabled': streamingLogMode == 'Date'}" >
                 <textarea class="sensor-config" name="fileLog">
"path_folder":"path",
"file_name":"filename",
"log_type":"file",
"maxFileNum":10,
"maxFileSize":10
                 </textarea>
              </div>
              <div style="padding-top:8px" ng-if=" input.fileLog != '' " ng-class="{ 'div-disabled': streamingLogMode == 'Date'}" >
                 <textarea class="sensor-config" name="fileLog"> @{{ input.fileLog  }}
                 </textarea>
              </div>
              <div style="padding-top:8px" ng-if=" input.dateLog == '' " ng-class="{'div-disabled': streamingLogMode == 'File'}">
                 <textarea class="sensor-config" name="dateLog">
"path_folder":"path",
"file_name":"filename",
"log_type":"date",
"maxDaysToRetain":10
                 </textarea>
              </div>
              <div style="padding-top:8px" ng-if=" input.dateLog != '' " ng-class="{ 'div-disabled': streamingLogMode == 'File'}" >
                 <textarea class="sensor-config" name="dateLog"> @{{ input.dateLog  }}
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
<script src="{{ asset('js/customJS/streamDataConfig/ang.gateway.config.js?v=10111A2') }}" charset="utf-8"></script>
<script src="{{ asset('js/plugins/switchery/switchery.js') }}" charset="utf-8"></script>
<script type="text/javascript">

  if (!String.prototype.trim) {
        String.prototype.trim = function() {
            return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
        };
  }

  
 
  var cm = new Array();
  function setCodeEditor(){
    cm = new Array();
    $("textarea.sensor-config").each(function(index){
      var myCodeMirror = CodeMirror.fromTextArea($(this)[0] , {
        lineNumbers: true,
        mode:  "javascript"
      });
      myCodeMirror.setSize( 500, 120);
      myCodeMirror.refresh(); 
      //console.log(myCodeMirror);
      
      cm.push(myCodeMirror);
    })
    
  }

  function clearCodeEditor(){
    if (cm != null && cm.length > 0){

      for (var i=0; i < cm.length; i++){
        //cm[i].toTextArea();
        //cm[i].getWrapperElement();
        cm[i].setOption("mode", "text/x-csrc");
        cm[i].getWrapperElement().parentNode.removeChild(cm[i].getWrapperElement());
        cm[i]=null;

      }
    }    
  }



  jQuery(document).ready(function($) {
    setTimeout(function(){
      setCodeEditor();
    },500);
   

    $('form').on('submit',function(){
      setTimeout(function(){
      $("div.loading-spinner").css("display","block");
      }, 500);
    });


  });


  function saveFormData(){
   
     var input = new Object();
         input.fileLog = $("textarea[name='fileLog']").val();
         input.dateLog = $("textarea[name='dateLog']").val();
         
      if (cm != null && cm.length > 1){
        
        input.fileLog = cm[0].getValue();
        input.dateLog = cm[1].getValue();
        
        //setCookie('cm',cm.length,10);
      }
          
      var formData = JSON.stringify(input);
      
      setCookie('dataLoggerSetting', formData,30);    
      console.log('Form data saved');        
  }


  function setCookie(cname, cvalue, minutes) {
    deleteCookie(cname);
    var d = new Date();
    d.setTime(d.getTime() + (minutes*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
 }

 function deleteCookie(cname) {
    var d = new Date(); //Create an date object
    d.setTime(d.getTime() - (1000*60*60*24*2)); //Set the time to the past. 1000 milliseonds = 1 second
    var expires = "expires=" + d.toGMTString(); //Compose the expirartion date
    window.document.cookie = cname+"="+"; "+expires;//Set the cookie with name and the expiration date
 
}


  window.onbeforeunload = function (e) {
    //var message = "Your confirmation message goes here.";
    saveFormData();  
  };



</script>
@endpush

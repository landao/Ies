@extends('layouts.app')
@push('styles')
<link href="{{ asset('/css/plugins/nouslider/jquery.nouislider.css') }}" rel="stylesheet">
<link href="{{ asset('/css/plugins/ionRangeSlider/ion.rangeSlider.css') }}" rel="stylesheet">
<link href="{{ asset ('css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/codemirror/codemirror.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/codemirror/ambiance.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/switchery/switchery.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="row" >
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
  
 
</style>
<div class="col-md-12">
  <div class="ibox ">
    <div class="ibox-title bg-title">
      <h5>Stream Data Processing</h5>
      <div class="ibox-tools">
      </div>
    </div>
    <div class="ibox-content">
      <div>
        <form method="post" action="" class="form-horizontal">
          @csrf
        
          <!-- <script type="text/javascript">              
              window.onload = function () {

                var loadData = angular.element(document.getElementById('StreamConfigCtrl')).scope().getCookieSetting2("streamSetting2");
                angular.element(document.getElementById('StreamConfigCtrl')).scope().getCookieSetting1("streamSetting1");

                if (loadData == 0){
                  angular.element(document.getElementById('StreamConfigCtrl')).scope().searchInitialize();
                }  
                              
              }
                               
            </script> -->
          

          <div class="form-group text-left"><label class="col-sm-6 control-label text-left">Setup</label>
            <div class="col-sm-6"></div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left"> Setting Name </label>
            <div class="col-sm-10">
              <input class="form-control" type="text" name="configName" value="" required>
            </div>
          </div>

          <div class="form-group"><label class="col-sm-2 control-label text-left"> Data Source IP </label>
            <div class="col-sm-10"><input class="form-control" type="text" name="hostName" value="" required></div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left"> Data Source Port </label>
            <div class="col-sm-10"><input class="form-control" type="text" name="port" value="" required> <span class="help-block m-b-none"> Port for Stream Data ( 1000 - 99999).</span>
            </div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left">Batch Time</label>
            <div class="col-sm-10">
              <div>
                <input class="form-control" type="text" name="batchtime" id="batchtime" >
              </div>
            </div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group"><label class="col-sm-2 control-label text-left">Enable Transfer Data to Cloud Storage?</label>
            <div class="col-sm-10">
              <div>
                <input type="checkbox" class="js-switch" name="toCloud" />
              </div>
            </div>
          </div>
          <div class="hr-line-dashed"></div>
          <div class="form-group" ng-repeat="_row in sensors track by _row.id">
            <label class="col-sm-2 control-label text-left"> Search For </label>
            <div class="col-sm-4" >
              <textarea class="sensor-config" name="code">
# coding: utf-8

# In[1]:


import time, sys
for i in range(8):
    print(i)
    #time.sleep(0.5)
    
for i in range(8):
    print(i)
    #time.sleep(0.5)
xy = "hello python"

print(xy)

a = 10
b= 20
print(a + b)

              </textarea>
            </div>

            <div class="col-sm-1"></div>

            <div class=" col-sm-3">
            
            <textarea class="python-result">
              
            </textarea>
          </div>
            

            
          </div>
                  
          <div class="hr-line-dashed"></div>
          <div class="form-group">
            <div class="col-sm-12">
              <div>
                <input type="hidden" name="sensorList" id='sensorList'>
                <input type="hidden" name="command" value="1">
                <button type="button" class="btn-primary" id="btnRunCode" >Submit</button>
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
  

  function batchTimeUpdate(value){
    if (slider){
      slider.update({
        from: value
      })
    }
  }

  var cm = new Array();
  var result = new Object();

  function setCodeEditor(){
    cm = new Array();
    $("textarea.sensor-config").each(function(index){
      var myCodeMirror = CodeMirror.fromTextArea($(this)[0] , {
        mode: "javascript",
        lineNumbers: true,
        matchBrackets: true,
        styleActiveLine: true,
        readOnly: false
        
      });
      myCodeMirror.setSize( 600, 240);
      myCodeMirror.on('change', function(el){
        var textToWrite = el.getValue();

      });
      cm.push(myCodeMirror);
    })

    $("textarea.python-result").each(function(index){
      var cmOut = CodeMirror.fromTextArea($(this)[0] , {
        mode: "javascript",
        lineNumbers: true,
        matchBrackets: true,
        styleActiveLine: true,
        readOnly: false
        
      });
      cmOut.setSize( 600, 240);

      cmOut.on('change', function(el){
        
      });
      
      result = cmOut;

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
    var slider = $("#batchtime").data("ionRangeSlider");

  });

      
  function saveFormData(){
   
     // var input = new Object();
     //      input.filterName = $("input[name='configName']").val();
     //      input.hostName = $("input[name='hostName']").val();
     //      input.port = $("input[name='port']").val();
     //      input.batchTime = $("input[name='batchtime']").val();
     //  var formData = JSON.stringify(input);
     //  setCookie('streamSetting1', formData,30);

     //  var searchfor = new Array();

     //  if (cm != null && cm.length > 0){
     //    for (var i=0; i < cm.length; i++){        
     //      searchfor.push( cm[i].getValue());
     //    }
     //    //setCookie('cm',cm.length,10);
     //  }

     //  if (searchfor.length > 0 ){
     //    var formData2 = JSON.stringify( searchfor );      
     //    setCookie('streamSetting2', formData2, 30);        
     //  }

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


  $("#btnRunCode").click(function(){

     var code = cm[0].getValue();

     $("div.loading-spinner").css("display","block");

     var runUrl = '{{ url("pycomp/puttofile" ) }}';
     var _token = $("input[name='_token']").val();

     $.post( runUrl, { code: code, _token : _token})
       .done(function( data ) {
         result.getDoc().setValue( String( data ));
         $("div.loading-spinner").css("display","none");
       })
       .fail(function(err){
         alert( JSON.stringify( err ));
       })
       ;

      


  });


  

</script>
@endpush

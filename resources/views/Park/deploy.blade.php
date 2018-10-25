@extends('layouts.app')
@push('styles')
<link href="{{ asset('/css/plugins/nouslider/jquery.nouislider.css') }}" rel="stylesheet">
<link href="{{ asset('/css/plugins/ionRangeSlider/ion.rangeSlider.css') }}" rel="stylesheet">
<link href="{{ asset ('css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
<link href="{{ asset ('css/jquery-ui-1.9.2.custom.min.css') }}" rel="stylesheet">
@endpush
@section('content')
<style type="text/css">
    .icon-fail{
       background-image: url("{{ asset('Koloria/Denided.png') }}") ;
       background-repeat: no-repeat;
      height: 32px;
       width: 100%;
    }
    .icon-success{
      background-image: url("{{ asset('Koloria/Valid.png') }}") ;
      background-repeat: no-repeat;
      height: 32px;
      width: 100%;
    }
    .container_result {
      background-color:wheat;
      width: 100%;
    }
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
<div class="row">
  <div class="col-md-8">
    <div class="ibox float-e-margins">
      <div class="ibox-title bg-title">
        <h5>Virtium Cluster Deploy</h5>  <span class="label label-primary">  </span>
        <div class="ibox-tools">
        </div>
      </div>
      <div class="ibox-content">
        <div>
          <div id="dialog" style="display:none" title="Dialog Title">
            <div>
              <table class="table table-bordered table-striped">
                @if(isset($console) && is_array($console))
                @foreach($console as $key => $value)
                <tr>
                  <td colspan="2" >
                    <strong>{{$key}}</strong>
                  </td>
                </tr>
                @if (is_array($value) && count($value) >0 )
                @foreach ($value as $k => $v)
                <tr>
                  <td></td>
                  <td>{{$v}}</td>
                </tr>
                @endforeach
                @endif
                @endforeach
                @endif
              </table>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>
                    #
                  </th>
                  <th>
                    Status
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($result as $item => $value)
                @if ($value == 1)
                <tr>
                  <td>
                    <div class="icon-success">
                    </div>
                  </td>
                  <td>
                    @php
                    echo App\Utilities\PyUtilities::getIpFromString($item);
                    @endphp
                  </td>
                </tr>
                @endif
                @endforeach
                <tr style="display:none">
                  <td colspan="2">
                    <div style="display: none">
                         <button class="btn btn-primary" id="dialog_link" type="button">
                          <strong>
                          Show SSH Logs
                          </strong>
                         </button>
                    </div>

                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class='row'>
          <div class="col-md-12">
            <div id="clusterData"></div>
          </div>
        </div>

        <div class="row">
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
                    <strong>Cluster Spark Master Loading ...</strong>
                  </div>

                </div>
              </div>
          </div>

        <div class="row">

          @if ( isset($out) && is_array($out))
           @foreach ($out as $k => $v )
              @if ($k === 'error')
                <div class="alert alert-danger">{{ $v }}</div>
              @endif

           @endforeach
          @endif

        </div>

      </div>
    </div>
  </div>
  </div>
@endsection
@push('scripts')
<script src="{{ asset('/js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('/js/jquery-ui-1.9.2.custom.min.js') }}" charset="utf-8"></script>

<script type="text/javascript">

    var opt = {
        autoOpen: false,
        modal: true,
        width: 750,
        height:650,
        title: 'Logs',
        buttons: {
            close: function () {
            $( this ).dialog( 'option', 'hide', 'explode' );
            $(this).dialog("close");
            }
        }
    };

    var theDialog = $("#dialog").dialog(opt);
    var sparkUrl = '{{ url('pypark/clustermaster/'. $masterIp) }}';

    function getClusterMasterData(){
      $.get( sparkUrl, function( data ) {
         $("#clusterData").html(data);
         $("div.loading-spinner").css("display","none");

         setTimeout(function(){
          getClusterMasterData();
        },1000);

      })
      .fail(function() {
        setTimeout(function(){
          getClusterMasterData();
        },1000);

        setTimeout(function(){
          $("div.loading-spinner").css("display","none");
        },15000);
      })
      ;

    }

    $(document).ready(function () {
        $('#dialog_link').click(function () {
        theDialog.dialog('open');
        return false;
        });

        setTimeout(function(){
          getClusterMasterData();
          $("div.loading-spinner").css("display","block");
        },1000);
    });






</script>
@endpush

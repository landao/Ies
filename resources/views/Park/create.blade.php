@extends('layouts.app')
@push('styles')
<link href="{{ asset('/css/plugins/nouslider/jquery.nouislider.css') }}" rel="stylesheet">
<link href="{{ asset('/css/plugins/ionRangeSlider/ion.rangeSlider.css') }}" rel="stylesheet">
<link href="{{ asset ('/css/plugins/ionRangeSlider/ion.rangeSlider.skinFlat.css') }}" rel="stylesheet">
@endpush
@section('content')
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

<div class="row">
  <div class="col-md-12">
    <div class="ibox float-e-margins">
      <div class="ibox-title bg-title">
        <h5>Setup Cluster</h5> 
        <div class="ibox-tools">
        </div>
      </div>
      <div class="ibox-content">
        <div class="row">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>
                  Existing Clusters
                </th>
                <th >
                  Workers
                </th>
                <th >
                  Cores
                </th>
                <th >
                  Memory
                </th>
                <th>
                  State
                </th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if (isset($masterinfo) && $masterinfo->url != '')
              <tr>
                <td>
                  Cluster 1 - {{ $masterIp }}
                </td>
                <td>
                  {{ $masterinfo->aliveworkers }}
                </td>
                <td>
                  {{ $masterinfo->cores }}
                </td>
                <td>
                  {{ $masterinfo->memory }}
                </td>
                <td>
                   {{ $masterinfo->status }}
                </td>
                <td>
                  
                </td>
              </tr>
              @if ($masterinfo->aliveworkers > 0)
                <tr>
                   <td colspan="">
                     <b>Workers</b>
                   </td>
                   <td><b>Cores</b> </td>
                   <td><b>State</b> </td>
                   <td colspan="3"></td>
                </tr>

              @foreach ( $masterinfo->workers as $wk )
                <tr>
                  <td colspan="">
                    {{ $wk['host'] }}
                  </td>
                  <td>
                    {{ $wk['cores'] }}
                  </td>
                  <td>
                    {{$wk['state']}}
                  </td>
                  <td colspan="3"></td>
                </tr>

              @endforeach
             @endif
              @else
              <tr>
                <td colspan="6">
                  <b>Cluster not existing.</b>
                </td>
              </tr>
              @endif

            </tbody>
          </table>
        </div>
        <div class="row">
          <form method="post" action="{{ action('ParkController@deploy') }}" class="form-horizontal">
            @csrf
            <table class="table table-bordered table-striped">
              <thead>                
                <tr>
                  <th>Cluster Name</th>
                  <th> IP </th>
                  <th class="text-center" > Master/Worker </th>                  
                  <th class="text-center" > Joined to Cluster </th>
                  <th class="text-center" > Action </th>                  
                  <th class="text-center" > Mount-Username </th>
                  <th class="text-center" > Mount-Password </th>
                </tr>
              </thead>
              <tbody> 
                @php
                  $row = 0;
                @endphp               
                @foreach ($ipList as $ip)
                @if ($ip != '')
                 @php
                  $row ++;
                 @endphp 
                <tr>
                   @if ($row == 1)
                    <td rowspan="{{ count($ipList) - 1  }}"> Cluster 1 - {{ $masterIp }} </td>

                   @endif
                  <td>{{ App\Utilities\PyUtilities::parseIpDiscovery( $ip )}}</td>
                  <td>
                    @if ( App\Utilities\PyUtilities::isMasterIp($ip) == 1 && App\Utilities\PyUtilities::isNASDevice($ip) == 0)
                      <input type="hidden" name="clusterMaster" value="{{$ip}}">
                      <span> Master </span>
                    @elseif (( App\Utilities\PyUtilities::isMasterIp($ip) != 1 ) && (App\Utilities\PyUtilities::isNASDevice($ip) != 1))
                      <span> Worker </span>
                    @elseif (App\Utilities\PyUtilities::isNASDevice($ip) == 1)
                      <span> NAS </span>
                       <input type="hidden" name="nasIp" value="{{ App\Utilities\PyUtilities::parseIpDiscovery( $ip ) }}">
                    @endif

                  </td>                                  
                  <td class="text-center">
                    @if (App\Utilities\PyUtilities::joinedToCluster($masterinfo,$ip) == 1)
                      <i class="fa fa-check fa-2x"></i>
                    @else

                    @endif

                  </td>
                  <td class="text-left">
                    @if (App\Utilities\PyUtilities::joinedToCluster($masterinfo,$ip) == 1 && App\Utilities\PyUtilities::isNASDevice($ip) == 0)

                      <!-- @if (App\Utilities\PyUtilities::isMasterIp($ip) == 1)
                         <span> Start </span><input type="checkbox" name="start_master_{{$ip}}" class="i-checks start" value="1"  checked="checked" disabled="disabled">
                      @else
                         <span> Start </span> <input type="checkbox" name="start_slave_{{$ip}}" id ="start_slave_{{$ip}}" class="i-checks start" value="1"  checked="checked" disabled="disabled">
                      @endif -->

                    @elseif (App\Utilities\PyUtilities::joinedToCluster($masterinfo,$ip) == 0 && App\Utilities\PyUtilities::isNASDevice($ip) == 0)

                      @if (App\Utilities\PyUtilities::isMasterIp($ip) == 1)
                         <input type="checkbox" name="start_master_{{$ip}}" class="i-checks start" value="1"  checked="checked">
                          <span> <b> Start </b> </span>
                      @else
                          <input type="checkbox" name="start_slave_{{$ip}}" id="start_slave_{{$ip}}" class="i-checks start" value="1"  checked="checked"> <span> <b> Attach </b> </span>
                      @endif

                    @endif

                    

                    @if (App\Utilities\PyUtilities::joinedToCluster($masterinfo,$ip) == 1 && App\Utilities\PyUtilities::isNASDevice($ip) == 0 )
                       @if (App\Utilities\PyUtilities::isMasterIp($ip) == 1)
                         <input type="checkbox" name="stop_master_{{$ip}}" class="i-checks stop" value="1" >
                         <span> <b> Stop Cluster</b> </span>
                       @else
                        <input type="checkbox" name="stop_slave_{{$ip}}" id="stop_slave_{{$ip}}" class="i-checks stop" value="1" >
                        <span> <b> Detach </b> </span> 
                       @endif
                     @endif

                  </td>
                 
                  <td class="text-center">
                    @if (App\Utilities\PyUtilities::isNASDevice($ip) == 1)
                     <input type="text" name="nas_user_{{ App\Utilities\PyUtilities::parseIpDiscovery( $ip ) }}" class="i-checks nas-user" value="{{ env('NAS_USER','') }}" >

                    @endif
                  </td>

                  <td class="text-center">
                    @if (App\Utilities\PyUtilities::isNASDevice($ip) == 1)

                     <input type="password" name="nas_pass_{{ App\Utilities\PyUtilities::parseIpDiscovery( $ip ) }}" class="i-checks nas-pass" value="{{ env('NAS_PASS','') }}" >

                    @endif
                  </td>

                </tr>
                @endif
                @endforeach
                @if (count($ipList) > 0 && $ipList[0] != '')
                <tr>
                  <td colspan="9">
                    <div class="text-left">
                      <button type="submit" class="btn btn-primary" id="btnDeploy">Deploy Cluster</button>
                    </div>
                  </td>
                </tr>
                @endif
              </tbody>
            </table>
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
                    <strong>Deploy in progress ...</strong>
                  </div>

                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="row">
          @php
           //var_dump($ipList);
          @endphp
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('/js/plugins/ionRangeSlider/ion.rangeSlider.min.js') }}" charset="utf-8"></script>
<script type="text/javascript">
$('.i-checks').iCheck({
checkboxClass: 'icheckbox_square-green',
radioClass: 'iradio_square-green',
});

$("#btnDeploy").click(function(){
setTimeout(function(){
$("div.loading-spinner").css("display","block");
}, 500);
});

</script>
@endpush

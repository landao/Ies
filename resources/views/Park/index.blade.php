@extends('layouts.app')
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
  <div class="col-md-8">
    <div class="ibox float-e-margins">
      <div class="ibox-title bg-title">
        <h5>Setup Clusters</h5> <span class="label label-primary"></span>
        <div class="ibox-tools">
          
        </div>
      </div>
      <div class="ibox-content">
        <div>
          <form method="post" action="{{url('pypark')}}" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-md-4"></div>
              <div class="form-group col-md-4">
                
              </div>
            </div>
           <!--  <div class="row">
              <div class="col-md-4"></div>
              <div class="form-group col-md-4">
                <label for="Name">Name:</label>
                <input type="text" class="form-control" name="name" value="Virtium Cluster Network" readonly="">
              </div>
            </div>
            -->
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
                    <strong>Searching for Devices ...</strong>
                  </div>
                  
                </div>
              </div>
            </div>

            <div>
              <div class="col-md-4">
                 <button type="submit" class="btn btn-success device-discover" id="btnDiscover">
                    <strong>
                     Add a new cluster
                    </strong>
                  </button>
              </div>
            </div>
        

        <div class="row">
          <div class="col-md-12">
            @if (isset($masterinfo) && $masterinfo->url != '')
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
                    <button class="btn btn-success device-discover" type="submit" >Edit</button>
                  </td>
                </tr>
                @else
                 <tr>
                   <td colspan="5">
                     <b>Cluster not existing.</b> 
                   </td>
                 </tr>

                @endif 

                <tr>
                  <td colspan="5">
                   
                  </td>
                </tr>
              </tbody>
            </table>  

            @endif

            
          </div>
        </div>

        </form>
        </div>

      </div>
    </div>
  </div>
  <div class="col-md-4">
    
  </div>

  

</div>
@endsection
@push('scripts')
<script type="text/javascript">

$("div.loading-spinner").css("display","none");

$(".device-discover").click(function(){
setTimeout(function(){
$("div.loading-spinner").css("display","block");
}, 500);

});
</script>
@endpush
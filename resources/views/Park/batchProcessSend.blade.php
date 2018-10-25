@extends('layouts.app')
@section('content')
<div class="row">
  <style type="text/css">
    .bg-title{
     background-color:#2f4050;
     color: #ffffff;
    }
    .text-left{
    text-align: left;
    }
    .td-icon{
    width: 60px;
    }
    </style>
  <div class="col-md-8">
    <div class="ibox float-e-margins">
      <div class="ibox-title bg-title">        
        <div class="col-md-8">
          <h5>Batch Data Setting</h5> <span class="label label-primary">  </span>
        </div>
        <div class="col-md-4">
          <div class="ibox-tools">
          </div>
        </div>

      </div>
      <div class="ibox-content">
        <div>
           @php

            var_dump($out);          

           @endphp
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">


</script>
@endpush
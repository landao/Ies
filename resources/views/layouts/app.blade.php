@extends('layouts.main')

@if (auth()->check())
@section('user-avatar', 'https://www.gravatar.com/avatar/' . md5(auth()->user()->email) . '?d=mm')
@section('user-name', auth()->user()->name)
@endif



@section('breadcrumbs')

@include('inspinia::layouts.main-panel.breadcrumbs', [
  'breadcrumbs' => [
    (object) [ 'title' => 'Home', 'url' => route('home') ]
  ]
])

@endsection



@section('sidebar-menu')
 <style type="text/css">
   
   .nav > li > button {
      /*color: #a7b1c2;*/
      font-weight: 600;
      padding: 14px 20px 14px 25px;
    }
 </style>
  <ul class="nav metismenu" id="side-menu" style="padding-left:0px;">
    <li class="active">
      <a href="{{ route('home') }}"><i class="fa fa-home"></i> <span class="nav-label">Home</span></a>
    </li>
    <li>
      <a>
        <button onclick="window.history.back()"> <i class="fa fa-arrow-left"></i> <span class="nav-label">Back</span></button>
      </a>
    	
    </li>
  </ul>


@endsection

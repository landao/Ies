<!DOCTYPE html>
<html lang="@yield('lang', config('app.locale', 'en'))">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Atnic">
    <title>@yield('title', config('app.name', 'INSPINIA'))</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Styles -->
    @section('styles')
    <link href="{{ asset('/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/inspinia.css') }}" rel="stylesheet">
    <link href="{{ asset('/fonts/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
    @show
    @stack('styles')
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @stack('head')
  </head>
  <body class="body-small {{ config('inspinia.skin', '') }}">
    <div id="wrapper">
      @include('layouts.sidebar.main')
      @include('layouts.main-panel.main')
    </div>
    @section('scripts')
    
    <script src="{{ asset('/js/jquery-2.1.1.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/bootstrap.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/plugins/metisMenu/jquery.metisMenu.js') }}" charset="utf-8"></script>    
    <script src="{{ asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!--  <script src="{{ asset('/js/vendor.js') }}" charset="utf-8"></script>  -->
    <script src="{{ asset('/js/inspinia.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/plugins/pace/pace.min.js') }}" charset="utf-8"></script>
    <!--   <script src="{{ asset('/js/manifest.js') }}" charset="utf-8"></script>  -->
    @show
    @stack('body')
    @stack('scripts')
  </body>
</html>
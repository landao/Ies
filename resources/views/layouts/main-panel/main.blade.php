<div id="page-wrapper" class="gray-bg">
  <div class="row border-bottom">
    <nav class="navbar navbar-static-top  " role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
        <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        <form role="search" class="navbar-form-custom" action="search_results.html">
          <div class="form-group">
            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
          </div>
        </form>
      </div>
      <ul class="nav navbar-top-links navbar-right">
        @if (Route::has('login'))
          @auth
            <li>
             <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
               @csrf
              <!--  {{ csrf_field() }} -->
             </form>
             <a href="" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out"></i>Logout
             </a>
            </li>

          @else
             <li> <a href="{{ route('login') }}">Login</a> </li>
             <li> <a href="{{ route('register') }}">Register</a> </li>                                            
          @endauth

        @endif


      </ul>
    </nav>
  </div>

  <!--
    <div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-sm-12">

      @section('breadcrumbs')
      <ol class="breadcrumb">
        <li>
          <a href="#">Home</a>
        </li>
        <li class="active">
          <strong>Breadcrumb</strong>
        </li>
      </ol>
      @show
    </div>
    {{-- <div class="col-sm-8">
      <div class="title-action">
        <a href="" class="btn btn-primary">This is action area</a>
      </div>
    </div> --}}
  </div>
 -->

  <div class="wrapper wrapper-content">
    @yield('content')
  </div>
  @include('inspinia::layouts.main-panel.footer.main')
</div>

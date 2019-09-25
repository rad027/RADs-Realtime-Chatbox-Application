@php
  use App\Models\UserInfo as UI;
  $user = Auth::guest() ? '' : Auth::user();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <link rel="shortcut icon" href="{{ config('app.favicon') }}" type="image/x-icon">
  <link rel="icon" href="{{ config('app.favicon') }}" type="image/x-icon">

  <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name') }}</title>
  <meta name="author" content="Roldhan Dasalla(iNew Works)" />
  <meta property="og:url" content="@if(trim($__env->yieldContent('meta_url')))@yield('meta_url')@else{{ config('app.meta.url') }}@endif" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="@if(trim($__env->yieldContent('meta_title')))@yield('meta_title'){{ ' | '.config('app.meta.title') }}@else{{ config('app.meta.title') }}@endif" />
  <meta property="og:image" content="@if(trim($__env->yieldContent('meta_image')))@yield('meta_image')@else{{config('app.meta.img')}}@endif" />
  <meta property="og:description" content="@if(trim($__env->yieldContent('meta_sdesc')))@yield('meta_sdesc')@else{{ config('app.meta.desc') }}@endif" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Font Awesome Icons -->
  <script type="text/javascript" src="https://kit.fontawesome.com/e24409b1ea.js"></script>
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ mix('/css/app.css').'?v='.md5(microtime()) }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script type="text/javascript">
    if(typeof AudioContext != "undefined" || typeof webkitAudioContext != "undefined") {
       var resumeAudio = function() {
          if(typeof g_WebAudioContext == "undefined" || g_WebAudioContext == null) return;
          if(g_WebAudioContext.state == "suspended") g_WebAudioContext.resume();
          document.removeEventListener("click", resumeAudio);
       };
       document.addEventListener("click", resumeAudio);
    }
  </script>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper" id="app" v-lazy-container="{ selector: 'img.lazy-load', error: 'https://cdn4.iconfinder.com/data/icons/ui-beast-3/32/ui-49-512.png', loading: 'https://3.bp.blogspot.com/-tqNEMUoveys/XHEx3U0mtCI/AAAAAAAACkg/ZDr8XOeRuOIVXIoaanSEu1m3z88cqWWsQCLcBGAs/s640/%2BLoading%2BGif%2BTransparent%2B%25281%2529.gif', attempt : 1 }">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light navbar-white">
    <div class="container">
      <a href="{{ url('/') }}" class="navbar-brand">
        <img data-src="{{ $user ? ($user->info()->first()->avatar ? $user->info()->first()->avatar : url('images/icon-user.png') ) : url('images/icon-user.png') }}" alt="RADs" style="width: 30px;height: 30px" class="no-shadow rounded-circle elevation-3 lazy-load"
             style="opacity: .8">
        Welcome, {{ is_object($user) ? $user->name : 'Guest' }}!
      </a>

      <!-- Left navbar links -->
      <ul class="navbar-nav">
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        @if(Auth::guest())

        @else
        <li class="nav-item dropdown no-arrow">
            <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small">{{ $user->name }}</span><img class="border rounded-circle img-profile lazy-load" data-src="{{ $user->info()->first()->avatar ? $user->info()->first()->avatar : url('images/icon-user.png') }}" style="width: 25px;height: 25px" /></a>
            <div class="dropdown-menu shadow dropdown-menu-right animated--grow-in" role="menu">
              <a class="dropdown-item" role="presentation" href="{{ url('profile') }}"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile</a>
              <a class="dropdown-item" role="presentation" href="{{ url('settings') }}"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings</a>
              <a class="dropdown-item" role="presentation" href="#"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i> Activity log</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </div>
        </li>
        @endif
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
              class="fas fa-th-large"></i></a>
        </li>
      </ul>
    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <header class="main">
      <img src="{{ config('app.logo.lg') }}" class="">
    </header>
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item {{ \Request::is('home') ? 'active' : '' }}">
              <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Games</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">News & Updates</a>
            </li>
          </ul>
          <ul class="navbar-nav navbar-right">
            @if(Auth::check())
            <li class="nav-item {{ \Request::is('register') ? 'active' : '' }}">
              <a href="{{ route('home') }}" class="nav-link">Dashboard</a>
            </li>
            @endif
          </ul>
        </div>
      </nav>      
    </div>
    @if (trim($__env->yieldContent('content_header')))
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"> @yield('content_header')</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    @endif

    <!-- Main content -->
    <div class="content">
      <div class="container">
        @yield('content')
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="container">
      <!-- To the right -->
      <div class="float-right d-none d-sm-inline">
        {{ config('app.name') }}
      </div>
      <!-- Default to the left -->
      <strong>Copyright &copy; {{ date('Y') }} <a href="#">RADs</a>.</strong> All rights reserved.
    </div>
  </footer>
  <div class="loading d-flex justify-content-center align-items-center position-fixed text-white" style="z-index: 9999999;top : 0; left: 0; width: 100%; height: 100%;background-color: rgba(52, 152, 219,0.9)">
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>&nbsp;<span>Loading...</span>
  </div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="{{ mix('/js/app.js').'?v='.md5(microtime()) }}"></script>
@yield('js')
<link rel="stylesheet" type="text/css" href="//ichord.github.io/At.js/dist/css/jquery.atwho.css">
<script type="text/javascript" src="//ichord.github.io/Caret.js/src/jquery.caret.js"></script>
<script type="text/javascript" src="//ichord.github.io/At.js/dist/js/jquery.atwho.js"></script>
<script type="text/javascript">
$(window).on('load',function(){
  $('div.loading').fadeOut(function(){
    $(this).remove();
  });
  @if(Auth::check())
  @php
    $d = [];
    foreach(UI::orderBy('id','desc')->cursor() as $u){
      $d[] = htmlentities($u->display_name);
    }
  @endphp
  app.members = {!! json_encode($d) !!};
  $('input.formTextBox').atwho({
    at: "@",
    data: app.members
  }); 
  @endif
});
$(document).ready(function(){
  @if(!Auth::guest())
  app.user = {
    info : {!! json_encode($user) !!},
    role : {!! json_encode($user->roles[0]) !!},
    extra : {!! json_encode($user->info()->first()) !!}
  };
  @else
  app.user = {
    info : {
      id : 0
    },
    extra : {
      display_name : 'dummy001'
    }
  };
  @endif
});
</script>
</body>
</html>

@php
  use App\Models\UserInfo as UI;
  $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    @auth
      window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
    @else
      window.Permissions = [];
    @endauth
  </script>  

  <!-- Font Awesome Icons -->
  <script type="text/javascript" src="https://kit.fontawesome.com/e24409b1ea.js"></script>
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ mix('/css/app.css').'?v='.md5(microtime()) }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  @yield('css')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper mainapp" id="app" v-lazy-container="{ selector: 'img.lazy-load', error: 'https://cdn4.iconfinder.com/data/icons/ui-beast-3/32/ui-49-512.png', loading: 'https://3.bp.blogspot.com/-tqNEMUoveys/XHEx3U0mtCI/AAAAAAAACkg/ZDr8XOeRuOIVXIoaanSEu1m3z88cqWWsQCLcBGAs/s640/%2BLoading%2BGif%2BTransparent%2B%25281%2529.gif', attempt : 1 }">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          {{ Auth::user()->name }} <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
      <img data-src="{{ config('app.logo.mini') }}" class="brand-image img-circle elevation-3 lazy-load"
           style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img data-src="{{ $user->info()->first()->avatar ? $user->info()->first()->avatar : config('app.logo.mini')  }}" style="height: 45px;width: 45px" class="img-circle elevation-2 lazy-load">
        </div>
        <div class="info text-break">
          <a href="{{ $user->hasVerifiedEmail() ? url('profile') : '#' }}" class="d-block">{{ $user->name }}</a>
          <p class="text-white">
            @foreach($user->getRoleNames() as $v)
              {{ $v }}&nbsp;
            @endforeach
          </p>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">GENERAL</li>
          <li class="nav-item">
            <a href="{{ url('home') }}" class="nav-link {{ \Request::is('home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          @if($user->hasRole(['Banned']) == false)
          <li class="nav-item">
            <a href="{{ url('profile') }}" class="nav-link {{ \Request::is('profile/*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                My Profile
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('settings') }}" class="nav-link {{ \Request::is('settings') ? 'active' : '' }}">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                Settings
              </p>
            </a>
          </li>
          @can(['onboard-view'])
          <li class="nav-header">DJ Tools</li>
          <li class="nav-item">
            <a href="{{ url('dj/onboard') }}" class="nav-link {{ \Request::is('dj/onboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>
                On Board
              </p>
            </a>
          </li>
          @can(['dj-list'])
          <li class="nav-item">
            <a href="{{ url('dj/list') }}" class="nav-link {{ \Request::is('dj/list') ? 'active' : '' }}">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>
                Disc Jockies
              </p>
            </a>
          </li>
          @endcan
          @endcan
          @endif
          @if($user->hasRole(['Developer','Owner','Co-Owner']))
          <li class="nav-header">Chatbox Tool</li>
          @if($user->hasRole('Developer'))
          @endif
          @if($user->hasRole(['Developer', 'Owner', 'Co-Owner']))
          <li class="nav-item">
            <a href="{{ url('cbox/messages') }}" class="nav-link {{ \Request::is('cbox/messages') ? 'active' : '' }}">
              <i class="nav-icon fas fa-comments"></i>
              <p>
                Messages
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('cbox/delete/all/messages') }}" class="nav-link" @click.prevent="deleteAllMessages">
              <i class="nav-icon fas fa-trash"></i>
              <p>
                Delete Messages
              </p>
            </a>
          </li>
          @endif
          @if($user->hasRole(['Developer', 'Owner', 'Co-Owner', 'Administrator']))
          <li class="nav-item">
            <a href="{{ url('cbox/trivia') }}" class="nav-link {{ \Request::is('cbox/trivia') ? 'active' : '' }}">
              <i class="nav-icon fas fa-robot"></i>
              <p>
                Bot Trivia
              </p>
            </a>
          </li>
          @endif
          <li class="nav-header">Site Tool</li>
          @if($user->hasRole('Developer'))
          <li class="nav-item">
            <a href="{{ url('site/meta') }}" class="nav-link {{ \Request::is('site/meta') ? 'active' : '' }}">
              <i class="nav-icon fas fa-info-circle"></i>
              <p>
                Meta
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('site/info') }}" class="nav-link {{ \Request::is('site/info') ? 'active' : '' }}">
              <i class="nav-icon fas fa-info"></i>
              <p>
                Information
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{ url('site/vpromotion') }}" class="nav-link {{ \Request::is('site/vpromotion') ? 'active' : '' }}">
              <i class="nav-icon fas fa-video"></i>
              <p>
                Video Promotion
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview {{ \Request::is(['site/images/fansigns', 'site/images/sponsor', 'site/images/advertisement']) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ \Request::is(['site/images/fansigns', 'site/images/sponsor', 'site/images/advertisement']) ? 'active' : '' }}">
                  <i class="nav-icon fas fa-images"></i>
                  <p>
                      Images
                      <i class="fas fa-angle-left right"></i>
                  </p>
              </a>
              <ul class="nav nav-treeview">
                  <li class="nav-item">
                      <a href="{{ url('site/images/fansigns') }}" class="nav-link {{ \Request::is('site/images/fansigns') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Fansigns</p>
                      </a>
                  </li>
                  @can('disabled-feature')
                  <li class="nav-item">
                      <a href="{{ url('site/images/sponsor') }}" class="nav-link {{ \Request::is('site/images/sponsor') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Sponsor</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="{{ url('site/images/advertisement') }}l" class="nav-link {{ \Request::is('site/images/advertisement') ? 'active' : '' }}">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Advertisement</p>
                      </a>
                  </li>
                  @endcan
              </ul>
          </li>          
          @endif
          @if($user->hasRole(['Developer','Owner','Co-Owner','Administrator']))
          <li class="nav-header">Admin Tools</li>
          <li class="nav-item">
            <a href="{{ url('tools/users') }}" class="nav-link {{ \Request::is(['tools/users', 'tools/users/*']) ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
              </p>
            </a>
          </li>
          @can('role-edit')
          <li class="nav-item">
            <a href="{{ url('tools/roles') }}" class="nav-link {{ \Request::is(['tools/roles', 'tools/roles/*']) ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Roles
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('tools/permissions') }}" class="nav-link {{ \Request::is(['tools/permissions', 'tools/permissions/*']) ? 'active' : '' }}">
              <i class="nav-icon fas fa-lock"></i>
              <p>
                Permissions
              </p>
            </a>
          </li>
          @endcan
          @endif
          <hr />
          <li class="nav-item">
            <a href="{{ url('/') }}" class="nav-link">
              <i class="nav-icon fas fa-home text-primary"></i>
              <p>
                Back to main page
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    @if (trim($__env->yieldContent('content_header')))
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
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
      <div class="container-fluid">
        @include('partials.form-status')
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
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      {{ config('app.name') }}
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; {{ date('Y') }} <a href="#">RADs</a>.</strong> All rights reserved.
  </footer>
  <div class="loading d-flex justify-content-center align-items-center position-fixed text-white" style="z-index: 9999999;top : 0; left: 0; width: 100%; height: 100%;background-color: rgba(52, 152, 219,0.9)">
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>&nbsp;<span>Loading...</span>
  </div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="{{ mix('/js/app.js').'?v='.md5(microtime()) }}"></script>
<link rel="stylesheet" type="text/css" href="//ichord.github.io/At.js/dist/css/jquery.atwho.css">
<script type="text/javascript" src="//ichord.github.io/Caret.js/src/jquery.caret.js"></script>
<script type="text/javascript" src="//ichord.github.io/At.js/dist/js/jquery.atwho.js"></script>
<script type="text/javascript" src="{{ url('js/readmore.min.js') }}"></script>
@yield('js')
<script type="text/javascript">

  $('#confirmDelete').on('show.bs.modal', function (e) {
    var message = $(e.relatedTarget).attr('data-message');
    var title = $(e.relatedTarget).attr('data-title');
    var form = $(e.relatedTarget).closest('form');
    $(this).find('.modal-body p').text(message);
    $(this).find('.modal-title').text(title);
    $(this).find('.modal-footer #confirm').data('form', form);
  });
  $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
      $(this).data('form').submit();
  });

</script>
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
</script>
<script type="text/javascript">
$(document).ready(function(){
  app.url_cover = '{{ empty($user->info()->first()->cover_photo) ? 'https://i.imgur.com/tNDY2Do.jpg' : $user->info()->first()->cover_photo }}';
  app.url_dp = '{{ empty($user->info()->first()->avatar) ? 'https://i.ibb.co/VQwZ9Yp/dhan.png' : $user->info()->first()->avatar }}';
  app.url_cover_old = '{{ empty($user->info()->first()->cover_photo) ? 'https://i.imgur.com/tNDY2Do.jpg' : $user->info()->first()->cover_photo }}';
  app.url_dp_old = '{{ empty($user->info()->first()->avatar) ? 'https://i.ibb.co/VQwZ9Yp/dhan.png' : $user->info()->first()->avatar }}';
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

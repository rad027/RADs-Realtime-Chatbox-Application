@extends('layouts.app')

@section('content')
<div class="login-box">
  <div class="login-logo">
    <a>&nbsp;</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      @if(count($errors) > 0)
      <div class="alert alert-danger rounded-0">
        <h5>OOPS! Something went wrong.</h5>
        <ul> 
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      <div class="social-auth-links text-center mb-3">
          <a href="{{ url('social/facebook/redirect') }}" class="btn btn-block btn-primary">
              <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
          </a>
      </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
@endsection

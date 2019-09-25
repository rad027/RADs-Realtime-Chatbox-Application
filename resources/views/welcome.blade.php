@php
use App\Models\Hashtag;
use App\Models\Chats;
use App\Models\UserInfo as UI;
@endphp

@extends('layouts.app')

@section('template_title','Home')

@section('content')
@include('partials.form-status')
<div class="row p-0 m-0 no-gutters">
  <div class="col-lg-8 pr-lg-2">
    <div class="card my-3 rounded-0 no-shadow border-0">
      <div class="card-body p-0">
        @if(!Auth::guest())
          @can('chat-send')
          <chat-form :reply="replied" v-on:messagesent="addMessage" :members="members" v-on:clearmsg="clearMsgs"></chat-form>
          @else
          <div class="alert alert-danger border-0 no-shadow rounded-0">
            You are currently ban from chatting.
          </div>
          @endcan
        @endif
        <div class="chatbox-main direct-chat direct-chat-primary">
          <chat-message v-on:rplyclicked="freply" :messages="messages"></chat-message>
        </div>
      </div>
      @if(Auth::guest())
      <div class="overlay">
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
        </div>
      </div>
      @else
      <div class="overlay loader-photo">
        <i class="fas fa-3x fa-sync-alt"></i>
      </div>
      @endif
    </div>
    <div class="embed-responsive embed-responsive-16by9 mt-3">
      <iframe class="embed-responsive-item" src="{{ config('app.video_embed_link') }}" allowfullscreen></iframe>
    </div>
  </div>
  <div class="col-lg-4">
    {{-- Right Panel --}}
    <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
      <div class="card-body p-0">
              <div class="album">
                <img src="https://static.gigwise.com/gallery/5209864_8262181_JasonDeruloTatGall.jpg" class="track-img" />
                <div class="title">
                  <small>
                    <span class="fa fa-play playbutton"></span>  Now Playing
                  </small>
                  <p class="song"></p>
                  <p class="listeners"><span></span> Listeners</p>
                </div>
              </div>
              <center>
                {{--<audio crossOrigin="anonymous" id="player" controls ref="foo" preload="none" style="width:16em;padding:0;"><source src="http://185.2.102.108:8018/stream?type=http&nocache=6" type='audio/mpeg'></audio>  --}}        
              </center>
              <div class="media ob">
                <img class="ml-2 mr-2 mt-1 ob-img" :src="onboard.dj_avatar" alt="...">
                <div class="media-body">
                  <p class="media-heading ob-name" v-html="onboard.dj_name">...</p>
                  <span :class="'fa fa-headphones ' + (onboard.dj_id==0 ? 'text-danger' : 'text-success')"></span> <span class="ob-status" v-if="onboard.dj_id==0">@{{ onboard.dj_tag }}</span><span class="ob-status" v-else>@{{ onboard.dj_tag }}</span>
                </div>
              </div>
      </div>
    </div>
    @if(Auth::check())
    <button class="btn btn-block btn-primary rounded-0 no-shadow" @click.prevent="requestSong">
      REQUEST A SONG
    </button>
    @endif
    <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
      <h5 class="card-header rounded-0">Online</h5>
      <div class="card-body online-wrapper p-1">
        <online-users :onlines="onlines"></online-users>
      </div>
    </div>
    <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
      <h5 class="card-header rounded-0">Trends</h5>
      <div class="card-body p-1 trend-box">
        <update-hashtag :hashtags="hashtags">Loading...</update-hashtag>
      </div>
    </div>
    {{-- Right Panel End --}}
  </div>
</div>
<div class="row no-gutters">
  {{-- Fansigns --}}
  <div class="col-lg-4 pr-lg-2">
    <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
      <h5 class="card-header rounded-0">Fansigns</h5>
      <div class="card-body p-1 fansigns-box">
        <center>
          <div id="carouselExampleInterval" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
              <div v-for="(img, index) in images_uploaded_fs" :class=" index == 0 ? 'carousel-item active' : 'carousel-item'">
                <img :data-src="'/images/'+img.image+'/fansigns'" class="d-block lazy-load" style="width: 350px;height: 500px">
              </div>
            </div>
          </div>
        </center>
      </div>
    </div>
  </div>
  {{-- Fansigns End --}}
  {{-- Advertisement --}}
  <div class="col-lg-4 pr-lg-2">
    <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
      <h5 class="card-header rounded-0">Advertisement</h5>
      <div class="card-body p-1 Aavertisement-box">
        <center>
          <img src="{{ url('samples/sample350x500.png/image/view') }}" class="d-block" style="width: 350px;height: 500px">
        </center>
      </div>
    </div>
  </div>
  {{-- Advertisement End --}}
  {{-- Sponsors --}}
  <div class="col-lg-4 pr-lg-2">
    <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
      <h5 class="card-header rounded-0">Sponsors</h5>
      <div class="card-body p-1 Sponsors-box">
        <center>
          <img src="{{ url('samples/sample350x500.png/image/view') }}" class="d-block" style="width: 350px;height: 500px">
        </center>
      </div>
    </div>
  </div>
  {{-- Sponsors End --}}
</div>
@endsection

@section('js')
<script type="text/javascript">
$(window).on('load',function(){
  /*var promise = document.querySelector('audio').play();

  if (promise !== undefined) {
      promise.then(_ => {
          // Autoplay started!
      }).catch(error => {
          // Autoplay was prevented.
          $('span.playbutton').click();
      });
  }*/
  $('.carousel').carousel({
    interval : 1500
  });
});
$(document).ready(function(){
  window.Echo.channel('chats')
        .listen('MessageSent', (e) => {
          window.app.messages.unshift(e.message);
        });
  window.Echo.channel('update-hashtag')
        .listen('UpdateHashtag', (uwu) => {
          if(uwu.status == 1){
            window.app.hashtags = uwu.text;
          }else{
            window.app.hashtags = [];
          }
        });
  window.Echo.channel('update-online')
          .listen('UpdateOnline', (res) => {
            if(res.status==1){
              window.app.onlines = res.onlines;
            }else{
              window.app.onlines = [];
            }
          });
  window.Echo.channel('update-onboard')
          .listen('UpdateOnBoard', (res) => {
            window.app.onboard = res.info;
          });
  window.app.getHashtag();
  window.app.getOnline();
  window.app.getOnBoard();
  window.app.getMessage();
  window.app.fetchFS();
  $('span.playbutton').click(function(){
      if($(this).hasClass('fa-pause')){
        $(this).removeClass('fa-pause').addClass('fa-play');
        document.getElementById('player').play()
      }else{
        $(this).removeClass('fa-play').addClass('fa-pause');
        document.getElementById('player').pause();
      }
  });
});
</script>
@stop

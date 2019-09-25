<div class="row p-0 m-0 no-gutters">
    <div class="col-lg-3 pr-lg-2">
        @if($user->hasRole(['Developer', 'Owner', 'Co-Owner', 'Administrator']))
        <div class="card bg-transparent my-3 rounded-0 no-shadow border-0">
            <div class="card-body p-0">
                <!-- /.info-box-content -->
                <div class="info-box mb-3 bg-success rounded-0 no-shadow">
                    <span class="info-box-icon"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Registered Users</span>
                        <span class="info-box-number">{{ \App\User::count() }}</span>
                    </div>
                </div> 
                <!-- /.info-box-content -->
                <div class="info-box mb-3 bg-info rounded-0 no-shadow">
                    <span class="info-box-icon"><i class="fas fa-envelope"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Chat Messages</span>
                        <span class="info-box-number">{{ \App\Models\Chats::count() }}</span>
                    </div>
                </div>   
                <!-- /.info-box-content -->
                <div class="info-box mb-3 bg-secondary rounded-0 no-shadow">
                    <span class="info-box-icon"><i class="fas fa-gamepad"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Games Available</span>
                        <span class="info-box-number">0</span>
                    </div>
                </div>   
                <!-- New Users -->
                <div class="card card-secondary rounded-0 no-shadow border-0">
                    <h5 class="card-header rounded-0">Latest Members</h5>
                    <!-- /.card-header -->
                    <div class="card-body py-3">
                        <ul class="users-list clearfix my-4 py-2">
                            @foreach(App\User::with('info')->orderBy('id','desc')->take(3)->get() as $u)
                            <li style="width: 30%">
                                <img class="lazy-load" data-src="{{ $u->info->first()->avatar }}">
                                <a class="users-list-name" href="{{ url('profile/'.strtolower(str_replace(' ', '.', $u->name))) }}">{{ $u->name }}</a>
                                <span class="users-list-date">{{ date('M d, Y', strtotime($u->created_at)) }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <!-- /.users-list -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-secondary text-center rounded-0 no-shadow border-0" v-b-tooltip.hover data-toggle="tooltip" data-placement="top" title="View All Users">
                        <a href="{{ route('users.index') }}">View All Users</a>
                    </div>
                    <!-- /.card-footer -->
                </div>                             
            </div>
        </div>  
        @else
        <button class="btn btn-block btn-primary mt-3 rounded-0 no-shadow" @click.prevent="requestSong">
          REQUEST A SONG
        </button>
        <div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
          <h5 class="card-header rounded-0">Online</h5>
          <div class="card-body online-wrapper p-1">
            <online-users :onlines="onlines"></online-users>
          </div>
        </div>
        @endif      
    </div>
    <div class="col-lg-6 pr-lg-2">
        <div class="card my-3 rounded-0 no-shadow border-0">
            <div class="card-body p-0">
                <chat-form :reply="replied" v-on:messagesent="addMessage" :members="members" v-on:clearmsg="clearMsgs"></chat-form>
                <div class="chatbox-main direct-chat direct-chat-primary">
                    <chat-message v-on:rplyclicked="freply" :messages="messages"></chat-message>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        @can('rs-list')
        <div class="card card-secondary my-3 rounded-0 no-shadow border-0">
            <h5 class="card-header rounded-0 clearfix">
                Song Request/s
                @can('rs-list-history')
                <span class="float-right">
                    <button class="btn btn-sm btn-primary" type="button" @click.prevent="getRequestHistory">HISTORY</button>
                </span>
                @endcan
            </h5>
            <div class="card-body p-0">
                <request-song :data="data"></request-song>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center bg-secondary rounded-0" @click.prevent="removeAllRequest">
                <a href="javascript:void(0)" class="uppercase">MARK ALL AS UNAVAILABLE</a>
            </div>
            <!-- /.card-footer -->
        </div>  
        @endcan      
    </div>
</div>

@section('js')
<script type="text/javascript">
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
});
</script>
@stop
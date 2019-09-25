@extends('layouts.app-b')

@section('template_title', 'On board')

@php
	use App\Models\Dj;
	use App\Models\OnBoard;
	$djs = new Dj();
@endphp

@section('content')
<div class="row p-0 m-0 no-gutters">
	<div class="col-lg-3 pr-lg-2">
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
		<div class="card card-secondary mt-3 rounded-0 no-shadow border-0">
			<h5 class="card-header rounded-0">
				On board
			</h5>
			<div class="card-body p-2">
				@can(['onboard-update'])
				<form method="post">
					@csrf
					<div class="form-group">
						<label>Disc Jockey :</label>
						@php
							$list = array();
							$list[''] = "Select a Dsic Jockey";
							$list['0'] = "Auto Tune";
							if($djs->count()):
								foreach($djs->orderBy('id','desc')->cursor() as $dj):
									$list[$dj->id] = $dj->dj_name;
								endforeach;
							endif;
						@endphp
						{!! Form::select('dj', $list, null, [ 'class' => 'form-control', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>Tag line :</label>
						{!! Form::text('tagline', old('tagline'), [ 'class' => 'form-control', 'placeholder' => 'Enter tag line here.', 'required' ]) !!}
					</div>
					<button class="btn btn-sm btn-outline-success rounded-0" type="submit" @click.prevent="onBoardUpdate">
						Update
					</button>
				</form>
				@else
				<div class="alert alert-danger rounded-0">
					<h3>Permission not granted!.</h3>
					<p>
						Sorry but you have no access on changing Dj On Board.
					</p>
				</div>
				@endcan
			</div>
		</div>
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
@can('onboard-history')
<div class="row">
	<div class="col-lg-12">
		<div class="card card-secondary my-3 rounded-0 no-shadow border-0">
			<h5 class="card-header">
				On board History (TOP RECENT 25)
			</h5>
			<div class="card-body table-responsive">
				<table class="table table-striped bundle">
					<thead>
						<tr>
							<th>ID</th>
							<th>DJ NAME</th>
							<th>DJ TAGLINE</th>
							<th>CREATED AT</th>
						</tr>
					</thead>
					<tbody class="ob-history-table">
						@if(OnBoard::count())
						@foreach(OnBoard::orderBy('id','desc')->cursor() as $ob)
						<tr>
							<td>{{ $ob->id }}</td>
							<td>{{ $ob->dj_name }}</td>
							<td>{{ $ob->dj_tag }}</td>
							<td>{{ date('M d, Y h:i a', strtotime($ob->created_at)) }}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endcan
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function(){
  window.Echo.channel('chats')
        .listen('MessageSent', (e) => {
          window.app.messages.unshift(e.message);
        });
  window.Echo.channel('update-onboard')
          .listen('UpdateOnBoard', (res) => {
            window.app.onboard = res.info;
          });
  window.app.getOnBoard();
  window.app.getMessage();
	$('select[name="dj"]').select2({ theme: 'bootstrap4' });
});
</script>
@stop

@section('css')

@stop
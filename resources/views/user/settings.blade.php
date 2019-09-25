@php
	$user = Auth::user();
@endphp
@extends('layouts.app-b')

@section('template_title', 'Settings')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="card my-2 rounded-0 border-0 no-shadow">
			<div class="card-body p-0">
			    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
			      <a class="nav-link active rounded-0" id="v-pills-pictures-tab" data-toggle="pill" href="#v-pills-pictures" role="tab" aria-controls="v-pills-pictures" aria-selected="true">Update Cover/DP</a>
			      <a class="nav-link rounded-0" id="v-pills-info-tab" data-toggle="pill" href="#v-pills-info" role="tab" aria-controls="v-pills-info" aria-selected="false">Information</a>
			      <a class="nav-link rounded-0" id="v-pills-upgrade-tab" data-toggle="pill" href="#v-pills-upgrade" role="tab" aria-controls="v-pills-upgrade" aria-selected="false">Upgrade</a>
			    </div>	
			</div>			
		</div>	
	</div>
	<div class="col-lg-8">
	    <div class=" my-2 tab-content" id="v-pills-tabContent">
	      <div class="tab-pane fade show active" id="v-pills-pictures" role="tabpanel" aria-labelledby="v-pills-home-tab">
	      	<div class="card card-primary rounded-0 no-shadow border-0">
	      		<h5 class="card-header rounded-0">
	      			Update Pictures
	      		</h5>
	      		<div class="card-body">
	      			<h5>Preview :</h5>
					<div class="card card-widget widget-user border-0 rounded-0 no-shadow mt-2">
					    <div v-if="url_cover" class="widget-user-header text-white p-5 rounded-0" :style="'background: url('+url_cover+') top center no-repeat;'">
					        <h3 class="widget-user-username text-right" style="text-shadow : 0px 1px #000">{{ $user->name }}</h3>
					        <h5 class="widget-user-desc text-right">{{ $user->roles->first()->name }}</h5>
					    </div>
					    <div class="widget-user-image">
					        <img v-if="url_dp" class="img-circle lazy-load" style="width: 90px;height: 90px" :data-src="url_dp" alt="User Avatar">
					    </div>
					    <div class="card-footer">
					        <div class="row">
					            <div class="col-sm-6 border-right">
					                <div class="description-block">
					                    <h5 class="description-header">{{ $user->chats()->count() }}</h5>
					                    <span class="description-text">CHATS</span>
					                </div>
					                <!-- /.description-block -->
					            </div>
					            <!-- /.col -->
					            <div class="col-sm-6">
					                <div class="description-block">
					                    <h5 class="description-header">0</h5>
					                    <span class="description-text">POSTS</span>
					                </div>
					                <!-- /.description-block -->
					            </div>
					            <!-- /.col -->
					        </div>
					        <!-- /.row -->
					    </div>
					</div>	
					@if($user->can('profile-update-photo'))      			
	      			<form method="post" action="{{ route('update.pictures') }}" enctype="multipart/form-data">
	      				@csrf
	      				<div class="form-group">
	      					<label>Cover Photo :</label>
							<div class="input-group">
							    <div class="custom-file text-truncate">
							        <input name="cover_photo" type="file" class="custom-file-input" id="exampleInputFile" @change="onFileChange_cover" accept="image/x-png,image/gif,image/jpeg">
							        <label class="custom-file-label cover_label text-truncate" for="exampleInputFile">Choose File</label>
							    </div>
							</div>	      					
	      				</div>
	      				<div class="form-group">
	      					<label>Display Photo :</label>
							<div class="input-group">
							    <div class="custom-file">
							        <input name="display_photo" type="file" class="custom-file-input text-truncate" id="exampleInputFile" @change="onFileChange_dp" accept="image/x-png,image/gif,image/jpeg">
							        <label class="custom-file-label dp_label text-truncate" for="exampleInputFile">Choose File</label>
							    </div>
							</div>	
	      				</div>
	      				<div class="form-group">
	      					<button class="btn btn-outline-warning rounded-0" type="reset" @click="clearPPProcess">CLEAR</button>
	      					<button class="btn btn-outline-success rounded-0" type="submit">CHANGE</button>
	      				</div>
	      			</form>
	      			@else
	      			<div class="my-2 alert alert-danger rounded-0">
	      				<h3>Permission not granted!.</h3>
	      				<p>
	      					We are sorry to say, But you current rank is not suitable to update your Cover photo or Display Photo. If you wish to upgrade, Contact us on our email at <a href="mailto:{{ env('APP_EMAIL','roldhandasalla27@gmail.com') }}" class="text-link">{{ env('APP_EMAIL','roldhandasalla27@gmail.com') }}</a>.
	      				</p>
	      			</div>
	      			@endif
	      		</div>
	      	</div>
	      </div>
	      <div class="tab-pane fade" id="v-pills-info" role="tabpanel" aria-labelledby="v-pills-info-tab">
	      	<div class="card card-primary rounded-0 no-shadow border-0">
	      		<h5 class="card-header rounded-0">
	      			Update Informations
	      		</h5>
	      		<div class="card-body p-2">
	      			<form class="my-2" method="post" action="{{ route('update.informations') }}">
	      				@csrf
	      				<div class="row">
	      					@can('update-name')
		      				<div class="col-md-6">
		      					<div class="form-group">
		      						<label>Main name :</label>
		      						{!! Form::text('main_name', $user->name, [ 'class' => 'form-control rounded-0', 'placeholder' => 'Enter desired main name here.' ]) !!}
		      					</div>
		      				</div>
		      				<div class="col-md-6">
		      					<div class="form-group">
		      						<label>Display name :</label>
		      						{!! Form::text('display_name', $user->info()->first()->display_name, [ 'class' => 'form-control rounded-0', 'placeholder' => 'Enter desired display name here.' ]) !!}
		      					</div>
		      				</div>
		      				@else
		      				<div class="col-md-12">
			      				<div class="alert alert-danger p-2 rounded-0">
			      					You`re not allowed to change your Main and Display Name with your current rank. <a href="#" id="v-pills-upgrade-tab" data-toggle="pill" href="#v-pills-upgrade" role="tab" aria-controls="v-pills-upgrade" aria-selected="false">Upgrade</a> to avail this feature, or contact us on email at <a href="mailto:{{ env('APP_EMAIL','roldhandasalla27@gmail.com') }}" class="text-link">{{ env('APP_EMAIL','roldhandasalla27@gmail.com') }}</a>.
			      				</div>
		      				</div>
		      				@endcan
		      				<div class="col-md-6">
		      					<div class="form-group">
		      						<label>Location :</label>
		      						{!! Form::text('location', $user->info()->first()->location, [ 'class' => 'form-control rounded-0', 'placeholder' => 'Enter desired location here.' ]) !!}
		      					</div>
		      				</div>
		      				<div class="col-md-6">
		      					<div class="form-group">
		      						<label>Skills :</label>
		      						{!! Form::text('skills', $user->info()->first()->skill, [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : web developer, typing fast, manloko ng babae' ]) !!}
		      					</div>
		      				</div>
		      				<div class="col-md-12">
		      					<div class="form-group">
		      						<label>Bio :</label>
		      						{!! Form::textarea('bio', $user->info()->first()->bio, [ 'class' => 'form-control rounded-0', 'placeholder' => 'Enter your bio here.', 'style' => 'resize:none', 'rows' => 5 ]) !!}
		      					</div>
		      				</div>
		      				<div class="col-md-12">
		      					<button class="btn btn-outline-success rounded-0" type="submit">UPDATE</button>
		      				</div>
	      				</div>
	      			</form>
	      		</div>
	      	</div>
	      </div>
	      <div class="tab-pane fade" id="v-pills-upgrade" role="tabpanel" aria-labelledby="v-pills-upgrade-tab">...</div>
	    </div>
	</div>
</div>
@stop

@section('css')
<style type="text/css">
#preview {
  display: flex;
  justify-content: center;
  align-items: center;
}

#preview img {
  max-width: 100%;
  max-height: 500px;
}
</style>
@stop
@section('js')
<script type="text/javascript">
$(function(){
  var hash = window.location.hash;
  hash && $('.nav-pills a[href="' + hash + '"]').tab('show'); 
  $('.nav-pills a').click(function (e) {
     $(this).tab('show');
  });
});
</script>
@stop
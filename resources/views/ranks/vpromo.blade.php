@extends('layouts.app-b')

@section('template_title', 'Video Promotion')

@section('content')
<div class="row">
	<div class="col-12 mt-3">
		<div class="card card-primary border-0 rounded-0 no-shadow">
			<h5 class="card-header">Editor</h5>
			<div class="card-body p-2">
				<form method="post">
					@csrf
					<div class="form-group">
						<label>Video Embed Link :</label>
						{!! Form::text('embed_link', config('app.video_embed_link'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'https://www.youtube.com/embed/vHS9E6JFja8?rel=0', 'required' ]) !!}
					</div>
					<button class="btn btn-success rounded-0" type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-12 mt-3">
		<div class="card card-primary border-0 rounded-0 no-shadow">
			<h5 class="card-header">Current Promoted Video</h5>
			<div class="card-body p-0">
			    <div class="embed-responsive embed-responsive-16by9">
			      <iframe class="embed-responsive-item" src="{{ config('app.video_embed_link') }}" allowfullscreen></iframe>
			    </div>
			</div>
		</div>
	</div>
</div>
@stop
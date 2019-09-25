@extends('layouts.app-b')

@section('template_title','Site Meta')

@section('content')
<div>
	<div class="col-12">
		<div class="card mt-3 border-0 rounded-0 no-shadow">
			<div class="card-body p-2">
				<form method="post">
					@csrf
					<div class="form-group">
						<label>META URL :</label>
						{!! Form::text('meta_url', config('app.meta.url'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : https://roldhandasalla.com/', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>META TITLE :</label>
						{!! Form::text('meta_title', config('app.meta.title'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : RADs Real-time System', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>META IMAGE :</label>
						{!! Form::text('meta_img', config('app.meta.img'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : https://roldhandasalla.com/logo.png', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>META DESCRIPTION :</label>
						{!! Form::textarea('meta_desc', config('app.meta.desc'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : This website is a real-time user-server interaction. It gives a user a new experience to new features.', 'required', 'rows' => 5, 'style' => 'resize:none' ]) !!}
					</div>
					<button class="btn btn-success rounded-0" type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
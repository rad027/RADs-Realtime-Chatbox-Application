@extends('layouts.app-b')

@section('template_title', 'Site Information')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card mt-3 border-0 rounded-0 no-shadow">
			<div class="card-body p-2">
				<form method="post">
					@csrf
					<div class="form-group">
						<label>Site Name : (<i class="text-danger" style="font-size: 11px">Updating this will results of automatic logout to restart all sessions.</i>)</label>
						{!! Form::text('site_name', config('app.name'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'Enter site name here.', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>Site Icon :</label>
						{!! Form::text('site_icon', config('app.favicon'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : https://domain-name.com/favicon.ico', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>Site Logo : (<i class="text-danger" style="font-size: 11px">Required size : 300px by 300px</i>)</label>
						{!! Form::text('site_logo', config('app.logo.lg'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : https://domain-name.com/logo.png', 'required' ]) !!}
					</div>
					<div class="form-group">
						<label>Site Mini Logo : (<i class="text-danger" style="font-size: 11px">Required size : 128px by 128px</i>)</label>
						{!! Form::text('site_mini_logo', config('app.logo.mini'), [ 'class' => 'form-control rounded-0', 'placeholder' => 'E.g : https://domain-name.com/mini-logo.png', 'required' ]) !!}
					</div>
					<button class="btn btn-success rounded-0" type="submit">UPDATE</button>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
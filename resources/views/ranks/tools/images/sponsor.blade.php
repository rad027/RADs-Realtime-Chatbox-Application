@extends('layouts.app-b')

@section('template_title', 'Site Sponsor')

@section('content')
<div class="row mt-3">
	<div class="col-12">
		<div class="card card-primary border-0 rounded-0 no-shadow">
 			<h5 class="card-header rounded-0">Update Sponsor</h5>
 			<div class="card-body p-1">
 				<sponsor-form></sponsor-form>
 			</div>
		</div>
	</div>
</div>
@stop

@section('js')

@stop
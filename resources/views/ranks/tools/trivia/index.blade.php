@extends('layouts.app-b')

@section('template_title', 'Bot Trivia')

@section('content')
<div class="row mt-3">
	<div class="col-12">
		<div class="card card-primary border-0 rounded-0 no-shadow">
			<h5 class="card-header rounded-0">Add new</h5>
			<trivia-form></trivia-form>
		</div>
	</div>
	<div class="col-12">
		<div class="card card-primary border-0 rounded-0 no-shadow">
			<h5 class="card-header rounded-0">List of Trivias</h5>
			<div class="card-body p-0 table-responsive">
				<trivia-list :list="trivia_list"></trivia-list>
			</div>
		</div>
	</div>
</div>
@stop

@section('js')
<script type="x-template" id="modal-body">
	<h2>UwU</h2>
</script>
@stop
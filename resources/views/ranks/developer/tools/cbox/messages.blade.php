@extends('layouts.app-b')

@section('template_title', 'Chatbox Messages Logs')

@section('content')
<div class="row mt-3">
	<div class="col-12">
		<form method="post">
			@csrf
			<div class="input-group mb-1">
			  <input type="text" name="search" class="form-control rounded-0" placeholder="Enter keyword here." aria-label="Recipient's username" aria-describedby="button-addon2">
			  <div class="input-group-append">
			    <button class="btn btn-primary rounded-0" type="button" id="button-addon2">Search</button>
			  </div>
			</div>
		</form>
		<div class="card border-0 rounded-0 no-shadow">
			<div class="card-body p-0 table-responsive">
				<table class="table table-striped" style="table-layout: fixed;">
					<thead>
						<tr>
							<th>ID</th>
							<th>SENDER</th>
							<th>MESSAGE</th>
							<th>TYPE</th>
							<th>CREATED AT</th>
						</tr>
					</thead>
					<tbody class="">
						@if($chats->count())
						@foreach($chats as $c)
						<tr>
							<td>{{ $c->id }}</td>
							<td>{{ $c->type != 'normal' ? config('app.name').' Bot' : $c->user->name }}</td>
							@php
								$msg = (object) array();
								if($c->reply->count()):
									$msg = (object) [
										'message' => $c->message,
							    		'replied' => [
											'status'	=>	1 ,
											'to_name'	=> $c->reply->first()->to_name,
											'from_name'		=>	$c->reply->first()->from_name,
											'text'		=>	$c->reply->first()->text
							    		]
									];
								else:
									$msg = (object) [
										'message' => $c->message,
							    		'replied' => [
											'status'	=>	0,
											'to_name'	=> '',
											'from_name'		=>	'',
											'text'		=>	''
							    		]
									];
								endif;
							@endphp
							<td style="width: 25%;" class="text-break">
								<div class="mmsg" style="overflow-y: hidden;">@if($c->type != 'normal'){!! $msg->message !!}@else{{ $msg->message }}@endif</div>
							</td>
							<td>
								{{ $c->type }}
							</td>
							<td>{{ date('M d, Y h:i a', strtotime($c->created_at)) }}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
				{{ $chats->links() }}
			</div>
		</div>
	</div>
</div>
@stop

@section('js')
<script type="text/javascript">
$(document).ready(function(){
	$('div.mmsg').readmore();
});
</script>
@stop
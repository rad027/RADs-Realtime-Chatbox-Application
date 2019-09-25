@extends('layouts.app-b')

@section('template_title','Permission Management')

@section('content_header', 'Permission Management')

@section('content')
<div class="card no-shadow rounded-0 border-0">
	<div class="card-body">
		<h4>Create new</h4>
		{!! Form::open(['method' => 'POST','route' => 'permissions.store','style'=>'display:inline']) !!}
		<div class="input-group mb-3">
		  <input type="text" class="form-control rounded-0" autofocus placeholder="Permission name here." name="name">
		  <div class="input-group-append">
		    <button class="btn btn-outline-success rounded-0" type="submit" id="button-addon2">CREATE</button>
		  </div>
		</div>
		{!! Form::close() !!}
		<h4>List</h4>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>NAME</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody>
					@foreach($permissions as $perm)
					<tr>
						<td>{{ $perm->id }}</td>
						<td>{{ $perm->name }}</td>
						<td>
					        {!! Form::open(['method' => 'DELETE','route' => ['permissions.destroy', $perm->id],'style'=>'display:inline']) !!}
					            {!! Form::button('Delete', ['type' => 'button', 'class' => 'btn btn-danger','data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user ?']) !!}
					        {!! Form::close() !!}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			{!! $permissions->links() !!}
		</div>
	</div>
</div>
@include('modals.delete-modal')
@stop
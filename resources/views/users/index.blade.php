@extends('layouts.app-b')

@php
  $user = Auth::user();
@endphp

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Users Management</h2>
        </div>
    </div>
</div>
<div class="card border-0 rounded-0 no-shadow">
  <div class="card-body table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>MAIN NAME</th>
          <th>DISPLAY NAME</th>
          <th>EMAIL</th>
          <th>ROLES</th>
          <th>JOINED DATE</th>
          <th>ACTIONS</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $d)
        <tr>
          <td>
            {{ $d->name }}
          </td>
          <td>
            {{ $d->info()->first()->display_name }}
          </td>
          <td>{{ $d->email }}</td>
          <td>
            @foreach($d->roles as $r)
            <span class="bg-secondary text-white p-1 mr-2">{{ $r->name }}</span>
            @endforeach
          </td>
          <td>{{ date('M d, Y h:i a', strtotime($d->created_at)) }}</td>
          <td>
            <a href="#" class="btn btn-sm btn-outline-success" @click.prevent="viewUser('{{ strtolower(str_replace(' ', '.', $d->name)) }}')">VIEW</a>
            @if($user->can('user-edit'))
            <a href="{{ route('users.edit',$d->id) }}" class="btn btn-sm btn-outline-danger">EDIT</a>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
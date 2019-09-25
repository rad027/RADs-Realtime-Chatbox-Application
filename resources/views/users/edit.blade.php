@extends('layouts.app-b')

@php
    $currentUser = Auth::user();
@endphp

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit New User</h2>
        </div>
    </div>
</div>

<div class="card rounded-0 border-0 no-shadow">
    <div class="card-body">
        <div class="callout callout-warning rounded-0">
            <h5><i class="icon fas fa-info"></i> Legend :</h5>

            <p>
                <ul>
                    <li>Neon 1 : <span class="neon-effect neon-1">SAMPLE DISPLAY NAME</span></li>
                    <li>Neon 2 : <span class="neon-effect neon-2">SAMPLE DISPLAY NAME</span></li>
                    <li>Neon 3 : <span class="neon-effect neon-3">SAMPLE DISPLAY NAME</span></li>
                    <li>Neon 4 : <span class="neon-effect neon-4">SAMPLE DISPLAY NAME</span></li>
                    <li>Neon 5 : <span class="neon-effect neon-5">SAMPLE DISPLAY NAME</span></li>
                    <li>Neon 6 : <span class="neon-effect neon-6">SAMPLE DISPLAY NAME</span></li>
                </ul>
            </p>
        </div>        
        {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Main Name :</strong>
                    {!! Form::text('name', null, array('placeholder' => 'Enter main name here.','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Display Name :</strong>
                    {!! Form::text('display_name', $user->info()->first()->display_name, array('placeholder' => 'Enter display name here.','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Location :</strong>
                    {!! Form::text('location', $user->info()->first()->location, array('placeholder' => 'Enter location here.','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Skills :</strong>
                    {!! Form::text('skill', $user->info()->first()->skill, array('placeholder' => 'Enter skills here.','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Email :</strong>
                    {!! Form::text('email', null, array('placeholder' => 'Enter email here.','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Role :</strong>
                    {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control','multiple')) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Neon Color :</strong>
                    @php
                        $list = [];
                        $list[0] = 'None';
                        for($i = 1;$i <= 6;$i++):
                            $list[$i] = 'Neon '.$i;
                        endfor;
                    @endphp
                    {!! Form::select('neon_color', $list, $user->info()->first()->neon_color, array('class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-md-12">
                <a class="btn btn-danger" href="{{ route('users.index') }}">CANCEL</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        {!! Form::close() !!}        
    </div>
</div>

@endsection

@section('js')
    @php
        if($currentUser->can('role-developer')):
            $remove = array();
        elseif($currentUser->can('role-owner')):
            $remove = array(
                "Developer", 
                "Owner"
            );
        elseif($currentUser->can('role-coowner')):
            $remove = array(
                "Developer", 
                "Owner", 
                "Co-Owner"
            );
        elseif($currentUser->can('role-admin')):
            $remove = array(
                "Developer", 
                "Owner", 
                "Co-Owner", 
                "Administrator"
            );
        endif;
    @endphp
<script type="text/javascript">
$(document).ready(function(){
    var list = @json($remove);
    if(list.length > 0){
        for(var i = 0; i < list.length; i++){
            $('option[value="'+list[i]+'"]').attr('disabled',true);
        }
    }
    $('select').select2({ theme: 'bootstrap4' });
});
</script>
@stop
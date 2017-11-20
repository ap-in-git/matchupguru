@extends('layouts.app')
@section('title')
User profile setting
@endsection
@section('content')
  <div class="container">
<h2 class="page-header text-center">User Profile</h2>
    <div class="row">
      <div class="col-sm-12">
        <form class="form-horizontal" method="POST" action="{{ route('user.profile.update') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">Email  *</label>
                 <div class="col-md-6">
                    <input id="email" type="email" class="form-control" name="email" value="{{$user->email }}" required autofocus maxlength="100" min="2">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="email" class="col-md-4 control-label">Name  *</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="name" value="{{$user->name }}" required autofocus maxlength="100" min="2">

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('magic_name') ? ' has-error' : '' }}">
                <label for="magic_name" class="col-md-4 control-label">Magic Name *</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="magic_name" value="{{$user->magic_name }}" required autofocus maxlength="100">

                    @if ($errors->has('magic_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('magic_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('gwent_name') ? ' has-error' : '' }}">
                <label for="gwent_name" class="col-md-4 control-label">Gwent Name *</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="gwent_name" value="{{$user->gwennt_name }}"  autofocus maxlength="100">

                    @if ($errors->has('gwent_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('gwent_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('heart_name') ? ' has-error' : '' }}">
                <label for="heart_name" class="col-md-4 control-label">Heartstone Name *</label>

                <div class="col-md-6">
                    <input id="name" type="text" class="form-control" name="heart_name" value="{{$user->heart_name }}"  autofocus maxlength="100">

                    @if ($errors->has('heart_name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('heart_name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Password</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" min="8">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                    Update Profile
                    </button>
                </div>
            </div>
        </form>
 <div>
 </div>
 </div>
 </div></div>



@endsection

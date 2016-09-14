@extends('app')

@section('body.tag')
    <body data-controller="profile" data-action="update">
    @endsection

    @section('content')

        <h1 class="text-center">MY DETAILS</h1>
        @include('partials.errors')
        <h4 class="text-center">UPDATE DETAILS</h4>
        <div class="col-md-4 col-md-offset-4">
            {!! Form::model($user, ['url'=>'profile/update-details', 'method'=>'POST', 'class'=>'form-horizontal']) !!}

            <div class="form-group">
                <label>First Name</label>
                <input type="text" class="form-control" name="first_name" value="{{$user->first_name}}">
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" class="form-control" name="last_name" value="{{$user->last_name}}">
            </div>

            <div class="form-group">
                <label>E-Mail Address</label>
                <input type="email" class="form-control" name="email" value="{{$user->email}}">
            </div>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control" name="password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>

            <div class="form-group">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary book-btn">
                        UPDATE
                    </button>
                </div>
            </div>
            </form>
        </div>
@endsection
@extends('app')

@section('body.tag')
    <body data-controller="admin" data-action="register">
    @endsection

    @section('content')

        <h1 class="text-center">MEMBERSHIP</h1>
        @include('partials.errors')
        <h4 class="text-center">ADD NEW MEMBER</h4>
        <div class="col-md-4 col-md-offset-4">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/administrator/save-user') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
                </div>

                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
                </div>

                <div class="form-group">
                    <label>E-Mail Address</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label>Member Type</label>
                    {!!Form::select('user_type', $types, null, ['class'=>'form-control'])!!}
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-primary book-btn">REGISTER</button>
                </div>
            </form>
        </div>
@endsection

@extends('app')

@section('body.tag')
    <body data-controller="admin" data-action="user">
    @endsection
    @section('content')
        <h1 class="text-center">MEMBERSHIP</h1>
        @include('partials.errors')
        <h4 class="text-center">EDIT MEMBER</h4>
        <div class="col-md-4 col-md-offset-4">
            <div id="playing_partner" class="form-group">
                <div class="form-group alert player-search">
                    <label class="form-group text-center">SEARCH FOR MEMBER</label>
                    <input type="text" class="typeahead form-control" placeholder="SEARCH FOR MEMBER"
                           autocomplete="off">
                </div>
                <form id="edit-user" class="form-horizontal" role="form" method="POST"
                      action="{{ url('/administrator/update-user') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    {!! Form::hidden('user_id')!!}
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="first_name"
                               value="{{ old('first_name') }}">
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
                        <button id="update_user" type="submit" class="btn btn-primary book-btn" disabled>UPDATE</button>
                    </div>
                </form>
            </div>
        </div>
@endsection


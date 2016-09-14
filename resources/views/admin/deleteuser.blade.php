@extends('app')

@section('body.tag')
    <body data-controller="admin" data-action="user">
    @endsection
    @section('content')
        <h1 class="text-center">MEMBERSHIP</h1>
        @include('partials.errors')
                <h4 class="text-center">DELETE MEMBER</h4>
                <div class="col-md-4 col-md-offset-4">
                    <div id="playing_partner" class="form-group">
                        <div class="form-group alert player-search">
                            <label class="form-group text-center">SEARCH FOR MEMBER</label>
                            <input type="text" class="typeahead form-control" placeholder="SEARCH FOR MEMBER"
                                   autocomplete="off">
                        </div>
                        <form id="delete-user" class="form-horizontal" role="form" method="POST"
                              action="{{ url('/administrator/delete-user') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {!! Form::hidden('user_id')!!}
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="first_name" value="">
                            </div>

                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="last_name" value="">
                            </div>

                            <div class="form-group">
                                <label>E-Mail Address</label>
                                <input type="email" class="form-control" name="email" value="">
                            </div>

                            <div class="form-group">
                                <label>Member Type</label>
                                <input type="text" class="form-control" name="user_type" value="">
                            </div>
                            <div class="form-group text-center">
                                <button type="button" class="btn btn-default btn-primary delete-user book-btn"
                                        data-toggle="modal" data-target="#deleteUserModal" disabled><i
                                            class="fa fa-trash"></i></button>
                            </div>
                    </div>
                </div>


        <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModal"
             aria-hidden="true">
            <div class="modal-dialog modal-sm delete-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" id="myModalLabel">REMOVE MEMBER</h5>
                    </div>
                    <div class="modal-body text-center">
                        <p>Do you want to remove this member?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary book-btn">REMOVE</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
@endsection

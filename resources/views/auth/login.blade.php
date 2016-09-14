@extends('app')

@section('body.tag')
    <body data-controller="login" data-action="index">
    @endsection
    @section('content')
        <div class="background-image"></div>
        <div class="col-md-4 col-md-offset-4 login-panel">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <a href="/">
                        <img src="{{asset('/images/logo.png')}}">
                    </a>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-group text-center">
                            <label>E-Mail</label>
                            <input type="email" class="form-control text-center" name="email"
                                   value="{{ old('email') }}">
                        </div>
                        <div class="form-group text-center">
                            <label>Password</label>
                            <input type="password" class="form-control text-center" name="password">
                        </div>
                        @if ($errors->any())
                            <ul class="list-unstyled alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li class="text-center">{{$error}}</li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary book-btn" style="margin-right: 15px;">LOGIN
                            </button>
                            <a href="/password/email">Forgot Your Password?</a>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer">
                <a class="text-center" href="http://rakeshmistry.co.nz/">Designed By:<br>Rakesh Mistry Web Development & Design</a>
            </div>
        </div>
@endsection

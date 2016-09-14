@extends('app')
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
                @if (session('status'))
                    <div class="text-center">
                        {{ session('status') }}
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group text-center">
                        <label>E-Mail Address</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary book-btn">
                            Send Password Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="{{ csrf_token() }}" name="csrf-token"/>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <title>Silverdale Squash Club</title>
    <link href="{{asset('css/all.css')}}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
@yield('body.tag')
@if (Auth::guest())
@else
    <header>
        <div class="container-fluid">
            <div class="col-md-12">
                <a class="logo" href="{{ url('/bookings/book-a-court') }}"><img src="{{asset('/images/logo.png')}}"></a>
                <div class="profile-area clearfix">
                    <div class="profile-image">
                        <a href="{{ url('/profile/upload-photo') }}"><img src="{{asset('/images/profile-image/'.Auth::user()->user_photo_file) }}" class="img-circle" alt="profile-image"></a>
                    </div>
                    <div class="name clearfix">
                        <span>WELCOME</span>
                        <a href="{{ url('/profile/my-details') }}"><span>{{Auth::user()->first_name}}</span></a>
                    </div>
                    <a class="lock" href="{{ url('/auth/logout') }}"><i class="fa fa-unlock"></i></a>
                </div>
                <div class="book-btn-head-div">
                    <a class="btn btn btn-info book-btn book-btn-head" role="button" href="{{ url('/bookings/book-a-court') }}">BOOK A COURT &nbsp; <i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
            </div>
    </header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header text-center">
                <div class="nav-title">BOOK A COURT</div>
                <button class="navbar-toggle collapsed"
                        type="button">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse navbar-left" id="bs-example-navbar-collapse-1">
                <a class=" nav brand" href="{{url('/bookings/book-a-court')}}"><img src="{{asset('/images/logo.png')}}"></a>
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"
                           role="button">BOOKINGS<i class="fa fa-chevron-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/bookings/my-bookings') }}">MY BOOKINGS</a></li>
                            <li><a href="{{ url('/bookings/book-a-court') }}">BOOK A COURT</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"
                           role="button">CLUB LADDER<i class="fa fa-chevron-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/ladder/leader-board') }}">LEADER BOARD</a></li>
                            <li><a href="{{ url('/ladder/my-matches/')}}">LADDER RESULTS</a></li>
                            <li>
                                <a href="{{url('/ladder/player-profile/'.Auth::user()->first_name.'-'.Auth::user()->last_name)}}">PLAYER
                                    PROFILE</a></li>
                            <li><a href="{{ url('/ladder/rules') }}">POINTS SYSTEM</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"
                           role="button">NOTICE BOARD<i class="fa fa-chevron-down"></i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/notifications/club-notices') }}">CLUB NOTICES</a></li>
                        </ul>
                    </li>
                    @if (in_array(\Auth::user()->user_type, config('squash.club+member')))
                        <li class="dropdown">
                            <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"
                               role="button">CLUB+ MEMBER<i class="fa fa-chevron-down"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/administrator/create-block-bookings') }}">MAKE A
                                        BOOKING</a></li>
                                <li><a href="{{ url('/administrator/block-bookings') }}">VIEW
                                        BOOKINGS</a></li>
                                <li><a href="{{ url('/administrator/create-notice') }}">ADD NOTICE</a></li>
                                <li><a href="{{ url('/administrator/notices') }}">REMOVE NOTICE</a></li>
                                @if(in_array(\Auth::user()->user_type, config('squash.administrator')))
                                    <li><a href="{{ url('administrator/register-member') }}">ADD NEW MEMBER</a></li>
                                    <li><a href="{{ url('/administrator/edit-member') }}">EDIT
                                            MEMBER</a></li>
                                    <li><a href="{{ url('/administrator/remove-member')}}">DELETE MEMBER</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li class="dropdown">
                        <a aria-expanded="false" class="dropdown-toggle" data-toggle="dropdown" href="#"
                           role="button">MY PROFILE
                            <i class="fa fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/profile/my-details') }}">UPDATE DETAILS</a></li>
                            <li><a href="{{ url('/profile/upload-photo') }}">UPDATE PROFILE PHOTO</a></li>
                        </ul>
                    </li>
                    <li class="logout"><a href="{{ url('/auth/logout') }}">LOGOUT</a></li>
                </ul>
            </div>
        </div>
    </nav>
@endif
<div class="container-fluid full-height biege">
    @yield('content')
</div>
@if(Auth::check())
    <footer class="footer">
        <div class="container text-center">

            <div class="sponsors">
                <div class="row">
                    CLUB SPONSORS
                </div>

                <div class="col-md-12">
                    <div class="col-md-4">
                        <img src="{{asset('/images/sponsors-logo/sponsor1.jpg')}}">
                    </div>
                    <div class="col-md-4">
                        <img src="{{asset('/images/sponsors-logo/sponsor2.jpg')}}">
                    </div>
                    <div class="col-md-4">
                        <img src="{{asset('/images/sponsors-logo/sponsor3.jpg')}}">
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endif
<div class="overlay overlay-hide">
    <div class="overlay-modal">
        <i class="fa fa-spinner fa-pulse fa-3x"></i>
        <h4 class="text-center">LOADING</h4>
    </div>
</div>
<!-- Scripts -->
<script src="{{asset('/js/app.js')}}"></script>
</body>
</html>
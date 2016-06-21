<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <title>Register | Task Manager</title>
    <link href="{{ asset('img/favicon.ico') }}" rel="icon" type="image/png" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/light-bootstrap-dashboard.css') }}" rel="stylesheet"/>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css" />
    <style>
        .register-page {
            background: #FB404B;
            background: -moz-linear-gradient(top, #FB404B 0%, rgba(187, 5, 2, 0.6) 100%);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #FB404B), color-stop(100%, rgba(187, 5, 2, 0.6)));
            background: -webkit-linear-gradient(top, #FB404B 0%, rgba(187, 5, 2, 0.6) 100%);
            background: -o-linear-gradient(top, #FB404B 0%, rgba(187, 5, 2, 0.6) 100%);
            background: -ms-linear-gradient(top, #FB404B 0%, rgba(187, 5, 2, 0.6) 100%);
            background: linear-gradient(to bottom, #FB404B 0%, rgba(187, 5, 2, 0.6) 100%);
            background-size: 150% 150%;
        }

        .register-page > .content {
            padding: 10vh 0;
        }
    </style>
</head>
<body>
    <div class="wrapper wrapper-full-page">
        <div class="register-page">
            <div class="content">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <div class="card">
                                <form id="register-form" method="POST" action="{{ route('register') }}">
                                    <div class="header text-center">
                                        <h4 class="title">REGISTER</h4>
                                    </div>
                                    <div class="content">
                                        @if(isset($errors))
                                            @foreach($errors->all() as $error)
                                                <div class="alert alert-danger alert-dismissable">
                                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                                    <b>Error!</b> {{ $error }}
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" placeholder="Name ..." value="{{ old('name') }}" />
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control" placeholder="Email ..." value="{{ old('email') }}" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control" placeholder="Username ..." value="{{ old('username') }}" />
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control" placeholder="Password ..." />
                                        </div>
                                        <div class="form-group text-center">
                                            {!! csrf_field() !!}
                                            <button type="submit" class="btn btn-fill btn-wd btn-primary">Register</button>
                                            <br/>
                                            have an account? <a href="{{ route('login') }}">login here</a>
                                        </div>
                                    </div>
                                    <div class="footer text-center">
                                        &copy; 2016 <a href="http://bagaskara.id">Bagakara Wisnu Gunawan</a>.
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-1.10.2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
</body>
</html>
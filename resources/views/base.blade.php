<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ isset($page) ? ucfirst($page) . ' | ' : '' }}Task Manager</title>
	<link href="{{ asset('img/favicon.ico') }}" rel="icon" type="image/png" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/animate.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/light-bootstrap-dashboard.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/pe-icon-7-stroke.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/sweetalert2.min.css') }}" rel="stylesheet" />
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,700,300" rel="stylesheet" type="text/css" />
    <style>
        textarea {
            resize: vertical;
        }
    </style>
    @yield('custom-css')
</head>
<body>
    <div id="wrapper" class="wrapper" v-cloak>
        <div class="sidebar" data-color="azure" data-image="img/sidebar-4.jpg">
        	<div class="sidebar-wrapper">
                <div class="logo">
                    <a href="{{ route('dashboard') }}" class="simple-text">
                        Task Manager
                    </a>
                </div>
                <div class="user">
                    <div class="photo">
                        <img src="{{ asset('img/default-avatar.png') }}" width="80px" height="80px" alt="{{ Auth::user()->name }}" />
                    </div>                 
                    <div class="info">
                        <span>Welcome back, {{ Auth::user()->name }}</span>
                    </div>
                </div>
                <ul class="nav">
                    <li class="{{ $page == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}">
                            <i class="pe-7s-graph"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="{{ $page == 'tasks' ? 'active' : '' }}">
                        <a href="{{ route('tasks') }}">
                            <i class="pe-7s-note"></i>
                            <p>Tasks</p>
                        </a>
                    </li>
                </ul>
        	</div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-default navbar-fixed">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{ route($page) }}">{{ ucfirst($page) }}</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                @if($notifications->count() > 0)
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-globe"></i>
                                        <b class="caret"></b>
                                        <span class="notification">{{ $notifications->count() }}</span>
                                    </a>
                                    <ul class="dropdown-menu todays-tasks">
                                        <li class="dropdown-header">Today's uncompleted tasks</li>
                                        <li class="divider"></li>
                                        @foreach($notifications as $key => $task)
                                            <?php
                                                $priority = 'Low';

                                                if ($task->priority == 1) {
                                                    $priority = 'Urgent';
                                                } elseif ($task->priority == 2) {
                                                    $priority = 'Normal';
                                                }
                                            ?>
                                            <li id="today-tasks-notification-{{ $task->id }}">
                                                <a href="{{ route('dashboard') }}">
                                                    <?php
                                                        $color = 'green';

                                                        if ($task->priority == 1) {
                                                            $color = 'red';
                                                        } elseif ($task->priority == 2) {
                                                            $color = 'blue';
                                                        }
                                                    ?>
                                                    {{ $task->name }} - <span style="color: {{ $color }}">{{ $priority }}</span>
                                                    <br/>
                                                    {{ str_limit($task->description, 30) }}
                                                </a>
                                            </li>
                                            @if(($key + 1) != $notifications->count())
                                                <li class="divider task"></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-globe"></i>
                                        <b class="caret"></b>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="no-task"><a href="{{ route('dashboard') }}">There are no tasks due today.</a></li>
                                    </ul>
                                @endif
                            </li>
                            <li>
                                <a href="{{ route('logout') }}">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <p class="copyright">
                        &copy; 2016 <a href="http://www.bagaskara.id" target="_blank">Bagaskara Wisnu Gunawan</a>, all rights reserved. Template by <a href="http://www.creative-tim.com" target="_blank">Creative Tim</a>.
                    </p>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('js/jquery-1.10.2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/vue.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/vue-strap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/vue-resource.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('js/light-bootstrap-dashboard.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    @yield('custom-js')
</body>
</html>
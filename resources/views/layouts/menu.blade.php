@section('control_menu')
<html>
<head>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
</head>
<body>
    <div>
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel sticky">
            <div class="container">


                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app_menu" aria-controls="app_menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="app_menu">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">


                        </li>


                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ url('/') }}">
                                download
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ url('/') }}">
                                Mail
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </nav>
    </div>

</body>


</html>
@endsection
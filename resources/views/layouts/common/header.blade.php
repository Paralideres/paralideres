<header class="header_area">

    <nav class="navbar navbar-default">

        <div class="container">

            <!-- Brand and toggle get grouped for better mobile display -->

            <div class="navbar-header">

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">

                    <span class="sr-only">Toggle navigation</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                </button>

                <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('images/logo.png')}}" alt=""></a>

            </div>



            <!-- Collect the nav links, forms, and other content for toggling -->

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                <ul class="nav navbar-nav navbar-right">

                    <li><a href="{{url('/')}}">inicio</a></li>

                    <li><a href="{{url('resources')}}">recursos</a></li>

                    @if($auth)

                    <li><a href="#" class="open_login">crear recurso</a></li>

                    @else

                        <li></li>

                    @endif

                    {{--<li><a href="#">icuestas</a></li>--}}

                </ul>

            </div><!-- /.navbar-collapse -->

            @if($auth)

            <div class="home_login" id="auth">

                <a href="#" v-on:click.prevent="logout">SALIR</a>

            </div>

            @else

            <div class="home_login">

                <a href="{{url('login')}}">Ingresar</a>

            </div>

            @endif

        </div><!-- /.container-fluid -->

    </nav>

</header>


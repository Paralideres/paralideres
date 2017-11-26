<header class="header_area recurso_header" @if($auth) id="auth" @endif>
    <nav class="navbar navbar-default">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('images/logo.png')}}" alt=""></a>
            </div>
            <div class="filter">
                <div class="filter_content">
                    <form action="{{url('/recursos')}}">
                        <select name="tag">
                            <option value="">...filter...</option>
                            @foreach($tags as $tag)
                            <option value="{{$tag->slug}}">{{$tag->label}}</option>
                            @endforeach
                        </select>

                        <input type="text" name="search_text" placeholder="Buscador de Recursos">
                        <input type="submit" id="searchsubmit" value="Search">
                    </form>
                </div>
                @if($auth)
                    <a href="#"  v-on:click.prevent="logout">SALIR</a>
                @else
                    <a href="{{url('/registrarme')}}">Registrarme</a>
                @endif
            </div>
        </div>
    </nav>
</header>
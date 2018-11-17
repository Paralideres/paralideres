<footer class="footer_area form_hide_m">

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <div class="footer_top_link text-center">

                    <a href="{{url('/resources')}}">VER RECURSOS</a>

                    <a href="http://blog.paralideres.org">CAPACITACIÓN</a>

                </div>

            </div>

            @foreach($categoriesCollections as $catColl)

            <div class="col-md-3">

                <div class="footer_content">

                    <h2><span><img src="{{asset('images/social-4.png')}}" alt=""></span>{{$catColl->label}}</h2>

                    <ul>

                        @foreach($catColl->collections as $collection)

                        <li><a href="{{url($collection->slug)}}">{{$collection->label}}</a></li>

                        @endforeach

                    </ul>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <p class="footer-link">ParaLideres.org © 2018 | <a href="#">Términos</a> | <a href="#">Privacidad</a> | <a href="#">Todos los derechos reservados</a></p>

</footer>



<!-- =========================

            FOR MOBILE

============================== -->

<footer class="footer_area footer_area_m form_hide_d">

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <div class="footer_top_link text-center">

                    <a href="{{url('/resources')}}">VER RECURSOS</a>

                    <a href="#">CAPACITACIÓN</a>

                </div>

            </div>

            @foreach($categoriesCollections as $catColl)

            <div class="col-xs-6">

                <div class="footer_content_m">

                    <ul>

                        @foreach($catColl->collections as $collection)

                            <li><a href="{{url($collection->slug)}}">{{$collection->label}}</a></li>

                        @endforeach

                    </ul>

                </div>

            </div>

            @endforeach

        </div>

    </div>

    <p>ParaLideres.org © 2018| <a href="#">Términos</a> | <a href="#">Privacidad</a> | <br> <a href="#">Todos los derechos reservados</a></p>

</footer>

<!-- =========================

    FOR MOBILE

============================== -->
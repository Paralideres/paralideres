@extends('layouts.layout')
@section('content')

<!-- =========================
    START BANNER SECTION
============================== -->
<section class="banner_area" @if(!$auth) id="auth" @endif>
    <div class="container">
        <div class="row">
            {{--<div id="banner_slider" class="owl-carousel owl-theme">--}}
                <div class="col-md-12 banner_wrapper">
                    <div class="banner_inner">
                        <h2>Hey Lider! Descubre lo nuevo de Paralideres!</h2>
                        <p class="form_hide_m">REGISTRATE Y RECIBE EN TU EMAIL LAS INSTRUCCIONES</p>
                        @if(!$auth)
                        <form id="signup_form" v-on:submit.prevent="signup('signup_form','signup')">
                            <div class="col-sm-3" :class="{'has-error':errors.email}">
                                <input type="email" class="form-control" name="email" v-on:keyup="signup('signup_form','')" placeholder="Ingresa tu email">
                                <span v-if="errors.email" class="has-error" v-text="errors.email[0]"></span>ver/yo descargar discurso
                            </div>
                            <div class="col-sm-3" :class="{'has-error':errors.password}">
                                <input type="password" class="form-control" name="password" v-on:keyup="signup('signup_form','')" placeholder="Ingresa una clave">
                                <span v-if="errors.password" class="has-error" v-text="errors.password[0]"></span>
                            </div>
                            <div class="col-sm-3" :class="{'has-error':errors.password_confirmation}">
                                <input type="password" class="form-control" name="password_confirmation" v-on:keyup="signup('signup_form','')" placeholder="Confirma tu clave">
                                <span v-if="errors.password_confirmation" class="has-error" v-text="errors.password_confirmation[0]"></span>
                            </div>

                            <div class="col-sm-3">
                                <button type="submit" :disabled="submitDisable" class="btn btn-dm form_hide_m">REGISTRARME</button>
                            </div>
                            <button type="submit" class="btn btn-dm m_submit"><img src="{{asset('images/submit-arrow.png')}}" alt=""></button>
                        </form>
                        @endif
                    </div>
                </div>

                {{--<div class="col-md-12 banner_wrapper">--}}
                    {{--<div class="banner_inner">--}}
                        {{--<h2>Hey Lider! Descubre lo nuevo de Paralideres!</h2>--}}
                        {{--<p class="form_hide_m">REGISTRATE Y RECIBE EN TU EMAIL LAS INSTRUCCIONES</p>--}}
                        {{--<form id="login_form" v-on:submit.prevent="login('login_form','login')">--}}
                            {{--<div class="col-sm-3">--}}
                                {{--<input type="email" class="form-control" name="email" v-on:keyup="login('login_form','')" placeholder="Ingresa tu email">--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-3">--}}
                                {{--<input type="password" class="form-control" v-on:keyup="login('login_form','')" placeholder="Ingresa una clave">--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-3">--}}
                                {{--<button type="button" id="contact_submit" :disabled="submitDisable" class="btn btn-dm form_hide_m">INGRESER</button>--}}
                            {{--</div>--}}
                            {{--<button type="button" id="contact_submit " class="btn btn-dm m_submit form_hide_d"><img src="images/submit-arrow.png" alt=""></button>--}}
                        {{--</form>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
</section>
<!-- =========================
    END BANNER SECTION
============================== --> 

<div id="app">
<!-- =========================
    START CATEGORY SECTION
============================== -->
<section class="cat_area">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="cat_left">
                    <h2><b>Categorias Populares:</b>
                        @foreach($categories as $category)
                            @if($category->id == 9 || $category->id ==11 || $category->id ==12)
                            <span class="cat-9" style="cursor: pointer" v-on:click.prevent="getCategoryResources('{{$category->slug}}')">{{$category->label}}</span>
                            @else
                            <span class="cat-{{$category->id}}" style="cursor: pointer" v-on:click.prevent="getCategoryResources('{{$category->slug}}')">{{$category->label}}</span>
                            @endif
                        @endforeach
                    </h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cat_right form_hide_m">
                    <h2>
                        <span>Buscador</span>
                        <input type="search" v-on:keyup.prevent="getResources('search', $event.target.value)" :placeholder="'Encuentra entre los '+resources.total+' recursos que tenemos'">
                    </h2>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- =========================
    END CATEGORY SECTION
============================== --> 

<!-- =========================
    START SERVICE SECTION
============================== --> 
<section class="service_area form_hide_m">
    <div class="container">
        <div class="row">
            <div class="col-md-4" v-for="(resource_info, index) in resources.data">
                <div class="service_inner">
                    <div class="service_head">
                        <h2>
                            <img v-if="resource_info.category && (resource_info.category.id == 9 || resource_info.category.id == 11 || resource_info.category.id == 12)" :src="asset+'images/icon/cat-icon-12.png'" alt="">
                            <img v-else-if="resource_info.category" :src="asset+'images/icon/cat-icon-'+resource_info.category.id+'.png'" alt="">
                            <span><a :href="base_url+'recursos?category='+resource_info.category.slug" v-text="resource_info.category.label"></a></span>
                        </h2>
                    </div>
                    <h4><a :href="base_url+'recursos/'+resource_info.slug">@{{ resource_info.title}}</a></h4>
                    <p>@{{ resource_info.review | truncate(200) }}</p>
                    <div class="author">
                        <h3>
                            <img width="45px" class="img-circle" v-if="resource_info.user.image" :src="asset+'uploads/'+resource_info.user.image" alt="">
                            <img width="45px" class="img-circle" v-else :src="asset+'images/user.png'" alt="">
                            author: <span v-text="resource_info.user.fullname"></span>
                        </h3>
                    </div>
                    <div class="comment">
                        <span><a :href="base_url+'recursos/'+resource_info.slug"><img :src="asset+'images/download.jpg'" alt="">ver/yo descargar discurso</a></span>
                        <span style="cursor: pointer" @if($auth) v-on:click.prevent="givenResourceLike(resource_info)" @else onclick="window.location.href='ingreser'" @endif>
                            <span v-if="resource_info.likes_count.length > 0" v-text="resource_info.likes_count[0].total"></span>
                            <span v-else>0</span>
                            <img v-if="resource_info.like.length > 0" :src="asset+'images/love3.png'" alt="">
                            <img v-else :src="asset+'images/love.jpg'" alt="">
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 clearfix text-center">
            <div class="service_btn">
                <a href="#" v-if="resources.next_page_url" v-on:click.prevent="getNextResources(resources.next_page_url)">ver mus recursus</a>
                <a href="#" v-else v-on:click.prevent="getNextResources(resources.prev_page_url)">ver mus recursus</a>
            </div>
        </div>

    </div>
</section>
<!-- =========================
    END SERVICE SECTION
============================== -->


<!-- =========================
    FOR MOBILE
============================== -->
    <section class="service_area form_hide_d">
        <div class="container">
            <div class="row">
                <div class="col-md-4 no-padding" v-for="(resource_info, index) in resources.data">
                    <div class="service_inner_m">
                        <span>
                            <img v-if="resource_info.category && (resource_info.category.id == 9 || resource_info.category.id == 11 || resource_info.category.id == 12)" :src="asset+'images/icon/cat-icon-12.png'" alt="">
                            <img v-else-if="resource_info.category" :src="asset+'images/icon/cat-icon-'+resource_info.category.id+'.png'" alt="">
                        </span>
                        <h4><a :href="base_url+'recursos/'+resource_info.slug">@{{ resource_info.title}}</a></h4>
                        <h3 v-if="resource_info.user.fullname" v-text="'author: '+resource_info.user.fullname"></h3>
                        <h3 v-else>author:</h3>
                        <a :href="base_url+'recursos/'+resource_info.slug"><img :src="asset+'images/download2.png'" alt=""></a>
                    </div>
                </div>
                <div class="col-md-12 clearfix text-center">
                    <div class="service_btn">
                        <a href="#" v-if="resources.next_page_url" v-on:click.prevent="getNextResources(resources.next_page_url)">ver mus recursus</a>
                        <a href="#" v-else v-on:click.prevent="getNextResources(resources.prev_page_url)">ver mus recursus</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- =========================
    FOR MOBILE
============================== -->


<!-- =========================
    START OPTION SECTION
============================== --> 
<section class="option_area" v-if="poll">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="option_head text-center">
                    <h2>encuesta</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="option_content">
        <div class="container">
            <div class="row">
                <form id="votePoll">
                <div class="col-md-12">
                    <div class="option_inner">
                        <h3 v-text="poll.question"></h3>
                        <ul>
                            <li v-for="(poll_option, index) in poll.options">
                                <div class="step_menu">
                                    <input type="radio" name="poll_option" :checked="index == 0" :value="poll_option.id" :id="index+1"  />
                                    <label :for="index+1"><span v-text="poll_option.option"></span></label>
                                    <br>
                                    <div v-if="pollResult">
                                        <label v-if="poll_option.votes">Total Vote : <span v-text="poll_option.votes.total"></span></label>
                                        <label v-else>Total Vote : 0</label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>                        
                </div>
                <div class="col-md-12 clearfix text-center">
                    <div class="service_btn service_btn_2">
                        @if($auth)
                            <a href="#" v-on:click.prevent="votePoll">ENVIAR MI VOTO</a>
                        @else
                            <a href="{{url('/ingreser?redirect=home')}}">ENVIAR MI VOTO</a>
                        @endif
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</section>                                  
<!-- =========================
    END OPTION SECTION
============================== -->
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/home.js')}}"></script>
@endsection





                               




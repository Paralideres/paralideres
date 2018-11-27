@extends('layouts.layout')
@section('content')
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-559b5a0c386679ae"
            async="async"></script>
    <div id="app">
        <!-- =========================
                START BANNER SECTION
        ============================== -->
        <section class="recurso_content_area form_hide_m">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <div class="recurso_main_content">
                            <div class="author_head">
                        <span>
                            @if($resource->user->image)
                                <img class="img-circle" src="{{asset('uploads/'.$resource->user->image)}}" alt=""
                                     width="50px">
                            @else
                                <img class="img-circle" src="{{asset('images/user.png')}}" alt="" width="50px">
                            @endif
                            @if($resource->category->id == 9 || $resource->category->id == 11 || $resource->category->id ==12)
                                <img src="{{asset('images/icon/cat-icon-12.png')}}" alt="">
                            @else
                                <img src="{{asset('images/icon/cat-icon-'.$resource->category->id.'.png')}}" alt="">
                            @endif
                            {{$resource->title}}
                        </span>
                                <span>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img
                                            src="{{asset('images/icon-2.png')}}" alt=""></button>
                                <ul class="dropdown-menu addthis_toolbox">
                                    <li><a class="addthis_button_email" style="margin-left: 0px"> Email</a></li>
                                    <li><a class="addthis_button_facebook" style="margin-left: 0px"> Facebook</a></li>
                                    <li><a class="addthis_button_twitter" style="margin-left: 0px"> Twitter</a></li>
                              </ul>

                            </div>
                                    @if($auth)
                                        <a href="#" :class="{'love_red_bg':userLike}"
                                           v-on:click.prevent="givenResourceLike('{{$resource->id}}')">
                                                <img v-if="userLike" src="{{asset('images/love-red.png')}}" alt="">
                                                <img v-else src="{{asset('images/love2.png')}}" alt="">
                                        </a>
                                    @else
                                        <a href="{{url('/login?redirect=resource&slug='.$resource->slug)}}"><img
                                                    src="{{asset('images/love2.png')}}" alt=""></a>
                                    @endif
                                    @if($auth && $auth['id'] == $resource->user_id)
                                        <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img
                                                    src="{{asset('images/icon-3.png')}}" alt=""></button>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a class="cursorPointer" v-on:click="openEditBox('{{$resource->id}}')"> Edit Resource</a></li>
                                            <li><a v-on:click="removeResource('{{$resource->id}}', '{{$auth['username']}}')"
                                                   class="cursorPointer"> Remove Resource</a></li>
                                      </ul>
                                    </div>
                                    @endif
                        </span>

                            </div>
                            <div class="author_desc">
                                <h4><strong>AUTOR :</strong> <a
                                            href="{{env('APP_URL')}}/resources?author={{$author['username']}}">{{$author['profile']['fullname']}}</a>
                                </h4>
                                <h2>RESUMEN</h2>

                                <p>{{$resource->review}}</p>

                                @if($resource->attachment)

                                    <h3>

                                        @if($auth)

                                            <span><a href="{{url('resources/'.$resource->id.'/'.$resource->slug.'/download')}}">Descargar Recurso</a> ( {{$resource->downloads->count()}}
                                                )</span>

                                        @else

                                            <span>Descargar Recurso ( {{$resource->downloads->count()}} )</span>

                                            Para descargar este recurso debes estar registrado en nuestro portal

                                            <a href="{{url('registrarme')}}">Registrate es fácil!</a>

                                        @endif

                                    </h3>

                                @endif

                            </div>
                            <div class="author_contect_wrapper">

                                @if($resource->attachment)

                                    <div class="author_contect_inner">

                                        <h2>{{$resource->title}}</h2>

                                        <iframe src="https://drive.google.com/viewerng/viewer?url={{asset('storage/resources/'.$resource->attachment)}}?pid=explorer&efh=false&a=v&chrome=false&embedded=true"
                                                style="width:100%; height:770px">

                                        </iframe>

                                    </div>

                                @else
                                    <div class="author_contect">
                                        <div class="author_contect_inner">

                                            <h2>{{$resource->title}}</h2>

                                            <h3></h3>

                                            <p>{!! $resource->content !!}</p>

                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="author_bottom">

                                <h4>Encuentra <span>recursos similares</span> a este haciendo click en cualquiera de las
                                    siguientes <span> etiquetas</span></h4>

                                @foreach($resource->tags as $tag)

                                    <a href="{{url('resources?tag='.$tag->slug)}}">{{$tag->label}}</a>

                                @endforeach

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sidebar text-right">
                            <h2>Módulo de publicidad</h2>
                            <img src="{{asset('images/sidebar-img.png')}}" alt="" class="img-responsive">
                            <h2>Créa tu equipo de trabajo hoy</h2>
                            <img src="{{asset('images/sidebar-img.png')}}" alt="" class="img-responsive">
                            <form id="votePollSideBar">
                                <div class="option_inner">
                                    <h3 class="title" v-text="poll.question"></h3>
                                    <ul>
                                        <li v-for="(poll_option, index) in poll.options">
                                            <div class="step_menu">
                                                <input type="radio" name="poll_option"
                                                       :checked="checkedAnswer > 0 && checkedAnswer == poll_option.id"
                                                       :value="poll_option.id" :id="index+1"/>
                                                <label :for="index+1"><span v-text="poll_option.option"></span></label>
                                                <br>
                                                <div v-if="pollResult">
                                                    <label v-if="poll_option.votes">Total Votación : <span
                                                                v-text="poll_option.votes.total"></span></label>
                                                    <label v-else>Total Votación : 0</label>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 text-left">
                                    <div class="service_btn service_btn_2">
                                        @if($auth)
                                            <a href="#" v-on:click.prevent="votePoll">ENVIAR MI VOTO</a>
                                        @else
                                            <a href="{{url('/login?redirect=home')}}">ENVIAR MI VOTO</a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                            <div class="sidebar_content">
                                <h2>ULTIMOS RECURSOS AGREGADOS</h2>
                                @foreach($latestResources as $latestResource)
                                    <h3>{{$latestResource->category->label}}</h3>
                                    <p>
                                        <a href="{{url('resources/'.$latestResource->id.'/'.$latestResource->slug)}}">{{$latestResource->title}} </a><br>
                                        Agregado el {{$latestResource->created_at->format('d M Y')}}</span> </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="recurso_content_area form_hide_d">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 no-padding">
                        <div class="recurso_main_content">
                            <div class="author_head_m">
                                <span></span>
                                <h3>{{$resource->title}}</h3>
                                <h4><strong>AUTOR :</strong> <a data-toggle="tooltip"
                                                                title="<img src='{{asset('images/author2.jpg')}}' />"
                                                                href="{{env('APP_URL')}}/resources?author={{$author['username']}}">{{$author['username']}}</a>
                                </h4>
                                <p>
                                    <span>PUBLICADO: @if($resource->user->created_at){{$resource->user->created_at->format('d M Y')}}@endif</span>

                                </p>
                            </div>
                            <div class="author_desc author_desc_m">
                                <p>{{$resource->review}}</p>
                            </div>
                            <div class="author_head author_head_m_2">
                                <span></span>
                                <span>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img
                                            src="{{asset('images/icon-2.png')}}" alt=""></button>
                                <ul class="dropdown-menu addthis_toolbox">
                                    <li><a class="addthis_button_email">Email</a></li>
                                    <li><a class="addthis_button_facebook">Facebook</a></li>
                                    <li><a class="addthis_button_twitter">Twitter</a></li>
                              </ul>
                            </div>
                                    @if($auth)
                                        <a href="#" :class="{'love_red_bg':userLike}"
                                           v-on:click.prevent="givenResourceLike('{{$resource->id}}')">
                                    <img v-if="userLike" src="{{asset('images/love-red.png')}}" alt="">
                                    <img v-else src="{{asset('images/love2.png')}}" alt="">
                                </a>
                                    @else
                                        <a href="{{url('/login?redirect=resource&slug='.$resource->slug)}}"><img
                                                    src="{{asset('images/love2.png')}}" alt=""></a>
                                    @endif
                                    @if($auth && $auth['id'] == $resource->user_id)
                                        <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><img
                                                    src="{{asset('images/icon-3.png')}}" alt=""></button>
                                        <ul class="dropdown-menu pull-right">
                                            <li><a class="cursorPointer" v-on:click="openEditBox('{{$resource->id}}')"> Editar Recurso</a></li>
                                            <li><a v-on:click="removeResource('{{$resource->id}}', '{{$auth['username']}}')"
                                                   class="cursorPointer">Eliminar Recurso</a></li>
                                      </ul>
                                    </div>
                                    @endif
                        </span>
                            </div>
                            <div class="author_contect">
                                @if($resource->attachment)
                                    <div class="author_contect">
                                        <div class="author_contect_inner">
                                            <h2>{{$resource->title}}</h2>
                                            <iframe src="https://drive.google.com/viewerng/viewer?url={{asset('uploads/'.$resource->attachment)}}?pid=explorer&efh=false&a=v&chrome=false&embedded=true"
                                                    style="width:100%; height:770px">
                                            </iframe>
                                        </div>
                                    </div>
                                @else
                                    <div class="author_contect_inner">
                                        <h2>{{$resource->title}}</h2>
                                        <h3></h3>
                                        <p>{!! $resource->content !!}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="author_desc">
                                @if($resource->attachment)
                                    <h3>
                                        @if($auth)
                                            <span><a href="{{url('resources/'.$resource->id.'/'.$resource->slug.'/download')}}">Descargar Recurso</a> ( {{$resource->downloads->count()}}
                                                ) </span>
                                        @else
                                            <span>Descargar Recurso ( {{$resource->downloads->count()}} )</span>
                                        @endif
                                    </h3>
                                @endif
                            </div>
                            <div class="author_bottom">
                                <h4>Encuentra <span>recursos similares</span> a este haciendo click en cualquiera de las
                                    siguientes <span> etiquetas</span>
                                    @if(!$auth)<a href="{{url('register')}}">Regístrate, ¡es fácil!</a>@endif
                                </h4>
                                @foreach($resource->tags as $tag)
                                    <a href="{{url('resources?tag='.$tag->slug)}}">{{$tag->label}}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- =========================
            END BANNER SECTION
        ============================== -->
        <!-- =========================
            START OPTION SECTION
        ============================== -->
        <section class="option_area form_hide_m">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="option_head text-center">
                            <h2>Otros Recursos</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="blog_content">
                <div class="container">
                    <div class="row">
                        @foreach($resource->category->resources as $otherResource)

                            <div class="col-md-3">

                                <div class="blog_content_inner">

                                    <h2>
                                        <a href="{{url('resources/'.$otherResource->id.'/'.$otherResource->slug)}}">{{$otherResource->title}}</a>
                                    </h2>

                                    <span>PUBLICADO {{$otherResource->created_at->format('d M Y')}}</span>

                                    <h3>AUTOR: {{$otherResource->user->fullname}} <br>

                                        @if($otherResource->user->created_at){{$otherResource->user->created_at->format('d M Y')}}@endif

                                    </h3>

                                    <p>{{$otherResource->review}}</p>

                                    @if($otherResource->category->id == 9 || $otherResource->category->id ==11 || $otherResource->category->id ==12)

                                        <img src="{{asset('images/icon/cat-icon-12.png')}}" alt="">

                                    @else

                                        <img src="{{asset('images/icon/cat-icon-'.$otherResource->category->id.'.png')}}"
                                             alt="">

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </section>
        <section class="option_area form_hide_d">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dot-round-title">
                            <div class="left-round-dot">&nbsp;</div>
                            <div class="real-title">similares</div>
                            <div class="right-round-dot">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="blog_content">

                <div class="container">

                    <div class="row">

                        <div class="col-md-3">

                            <div id="test_slider" class="owl-carousel owl-theme">

                                @foreach($resource->category->resources as $otherResource)

                                    <div class="blog_content_inner">

                                        <h2>
                                            <a href="{{url('resources/'.$otherResource->id.'/'.$otherResource->slug)}}">{{$otherResource->title}}</a>
                                        </h2>

                                        <span>PUBLICADO {{$otherResource->created_at->format('d M Y')}}</span>

                                        <h3>AUTOR: {{$otherResource->user->fullname}}
                                            <br> @if($otherResource->user->created_at){{$otherResource->user->created_at->format('d M Y')}}@endif
                                        </h3>

                                        <p>{{$otherResource->review}}</p>


                                        @if($otherResource->category->id == 9 || $otherResource->category->id ==11 || $otherResource->category->id ==12)

                                            <img src="{{asset('images/icon/cat-icon-12.png')}}" alt="">

                                        @else

                                            <img src="{{asset('images/icon/cat-icon-'.$otherResource->category->id.'.png')}}"
                                                 alt="">

                                        @endif

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        <!-- =========================
            END OPTION SECTION
        ============================== -->
        @if($auth)
            <div class="popup_content" id="edit_resource_popup">
                <form id="edit_resource_form" enctype="multipart/form-data">
                    <input type="hidden" name="id" v-model="recurso.id">
                    <div class="login_content create_resourse">
                        <div class="step_1">
                            <h2>Editar recurso - Paso 1 de 3 <br>
                                <span>Completa los siguientes campos y da Click en Continuar </span>
                                <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                                     alt=""></h2>
                            <div class="login_inner clearfix">

                                <div class="input_content clearfix" :class="{'has-error':errors1.title}">
                                    <label>Título Recurso</label>
                                    <input type="text" name="title" required id="input_text" placeholder="Escribe algo aquí..."
                                           v-model="recurso.title"
                                           class="noMargin">
                                    <p class="custom-err-msg">
                                        <span v-if="errors1.title" v-text="errors1.title[0]"></span>
                                    </p>
                                </div>
                                <div class="input_content clearfix" :class="{'has-error':errors1.review}">
                                    <label>Reseña o Resúmen</label>
                                    <textarea name="review" id="" required cols="30" rows="10" class="noMargin" v-model="recurso.description"></textarea>
                                    <p class="custom-err-msg">
                                        <span v-if="errors1.review" v-text="errors1.review[0]"></span>
                                    </p>
                                </div>
                                <div class="input_content clearfix" :class="{'has-error':errors1.category_id}">
                                    <label>CATEGORIA</label>
                                    <select name="category_id" class="noMargin" required v-model="recurso.cat_id" v-on:change="categorySelect">
                                        <option value="">Categoría</option>
                                        @if(isset($popup_categories))
                                            @foreach($popup_categories as $category)
                                                <option value="{{$category->id}}">{{$category->label}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="custom-err-msg">
                                        <span v-if="errors1.category_id" v-text="errors1.category_id[0]"></span>
                                    </p>
                                </div>
                                <div class="input_content clearfix" id="old_tag">
                                    <label>ETIQUETAS</label>
                                    {{--{{$popup_tags}}--}}
                                    <select id="select2" multiple name="tag_ids[]" required>
                                        <option value="">Etiquetas</option>
                                        @if(isset($popup_tags))
                                            @foreach($popup_tags as $tag)
                                                <option value="{{$tag->id}}">{{$tag->label}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="hints">Elija o cree una nueva etiqueta escribiendo una nueva</span>
                                    {{--<a class="new_tag" href="#" v-on:click.prevent="newTag">Crear nueva etiqueta</a>--}}
                                </div>
                                <button v-on:click.prevent="createResourceSetp1" class="resource_2">Continuar</button>
                                <button v-on:click.prevent="closePopup" class="resource_1">Cancelar</button>
                            </div>
                        </div>
                    </div>
                    <div class="login_content create_resourse">
                        <div class="step_2 clearfix">
                            <h2>Editar recurso - Paso 2 de 3 <br> <span>Elije la Opción de tu preferencia </span>
                                <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                                     alt=""></h2>
                            <div class="login_inner clearfix">

                                <div class="upload_file_area">
                                    <p><span>Sube tu contenido,</span> puedes hacer con un archivo o copiando el texto. <span>Elije una opción:</span>
                                    </p>
                                    <div class="upload_file_wrapper clearfix">
                                        <div class="upload-recurso-box" v-on:click="option2">
                                            <div class="upload-recurso-box-content">
                                                <img src="{{asset('images/file-up-img.png')}}" alt="">
                                                <p>CLICK PARA SUBIR TU ARCHIVO</p>
                                            </div>
                                        </div>
                                        <div class="upload-recurso-box" v-on:click="option1">
                                            <div class="upload-recurso-box-content">
                                                <img src="{{asset('images/file-up-img-2.png')}}" alt="">
                                                <p>USAR EL EDITOR DE TEXTO</p>
                                            </div>
                                        </div>
                                        {{--<div class="upload_text" v-on:click="option2">
                                            <div class="upload_text_inner">
                                                <img src="{{asset('images/file-up-img-2.jpg')}}" alt="">
                                                <p>CLICK PARA SUBIR TU ARCHIVO</p>
                                            </div>
                                        </div>--}}
                                    </div>
                                </div>
                                <p class="noMargin noPadding text-center"><a href="#" v-on:click.prevent="back1">VOLVER AL PASO
                                        ANTERIOR</a></p>
                                <br><br><br>
                                <button v-on:click.prevent="createResourceSetp3" class="resource_2">Finalizar edición y Subir</button>

                            </div>
                        </div>
                    </div>
                    <div class="login_content create_resourse">
                        <div class="step_3">
                            <h2>Editar recurso - Paso 3 de 3 <br> <span>Escribe el contenido de tu Colaboracion </span>
                                <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                                     alt=""></h2>
                            <div class="login_inner clearfix">

                                <div class="text_area_file clearfix" :class="{'has-error':errors2.content}">
                                    <p>CONTENIDO</p>
                                    <textarea style="margin-bottom: 40px!important;" name="content" id="" cols="30" rows="10"
                                              v-model="recurso.content"
                                              placeholder="Escribe tu contenido aqui."></textarea>
                                    <span style="padding-left: 0px!important;" v-if="errors2.content" class="has-error"
                                          v-text="errors2.content[0]"></span>
                                </div>
                                <span class="up_left_span"><a href="#"
                                                              v-on:click.prevent="back2">VOLVER AL PASO ANTERIOR</a></span>
                                <button v-on:click.prevent="createResourceSetp2" class="resource_2">Subir</button>
                                <button v-on:click.prevent="closePopup" class="resource_1">Cancelar</button>

                            </div>
                        </div>
                    </div>
                    <div class="login_content create_resourse">
                        <div class="step_4">
                            <h2>Editar recurso - Paso 3 de 3 <br> <span>Sube tu archivo</span>
                                <img v-on:click.prevent="closePopup" class="cross_ic" src="{{asset('images/cross_ic.jpg')}}"
                                     alt=""></h2>
                            <div class="login_inner final_step_wrapper clearfix">

                                <div class="final_step">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <h3>
                                            <img :src="recurso.catThumb" alt="">
                                            @{{ recurso.title }}
                                        </h3>
                                        <p v-text="recurso.description"></p>
                                    </div>
                                </div>
                                <div class="input_content final_step_input clearfix" :class="{'has-error':errors3.attach}">
                                    <div class="col-sm-2">
                                        <label class="text-center">ARCHIVO</label>
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="file" name="attach" placeholder="Elije tu archivo"
                                               style="padding: 12px!important;">
                                        <span style="margin-top: 0px!important;" v-if="errors3.attach" class="has-error"
                                              v-text="errors3.attach[0]"></span>
                                        <p>PDF, DOC, DOCX, PPT, PPTX, RTF, TXT</p>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                            <span class="up_left_span">
                            <a href="#" v-on:click.prevent="back2">VOLVER AL PASO ANTERIOR</a>
                        </span>
                                    <button v-on:click.prevent="createResourceSetp3" class="resource_2">Subir</button>
                                    <button v-on:click.prevent="closePopup" class="resource_1">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="sr-overlay"></div>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        var userLike = '<?php if (count($resource->like) > 0) {
            echo true;
        } else {
            false;
        }?>';

    </script>
    <script type="text/javascript" src="{{asset('js/resource.js')}}?js={{uniqid()}}"></script>
@endsection
@extends('layouts.layout')
@section('content')
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-559b5a0c386679ae" async="async"></script>

<?php 
$tag_slug=(isset($_GET['tag']))?$_GET['tag']:'';
$cat_slug=(isset($_GET['category']))?$_GET['category']:'';
$author=(isset($_GET['author']))?$_GET['author']:'';
?>

<section class="service_area recurso_list" id="app">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="recurso_filter clearfix">
                    <h2> Resultado de Busqueda  para:  <span></span></h2>
                    <form id="filterResource" v-on:submit.prevent="filterResource">
                        <span class="num" v-if="resources.data && resources.data.length > 0" v-text="resources.total+' Recursos'"></span>
                        <span class="num" v-else> 0 Recursos</span>
                        <span>
                                POR Etiqueta
                                <select name="tag_id" v-on:change="filterResource">
                                  <option value="">...filter...</option>
                                  @foreach($tags as $tag)
                                        <option value="{{$tag->id}}" @if($tag_slug == $tag->slug) selected @endif>{{$tag->label}}</option>
                                  @endforeach
                                </select>
                            </span>
                        <span>
                                POR CATEGORIA
                                <select name="category_id" v-on:change="filterResource">
                                  <option value="">..filter..</option>
                                  @foreach($categories as $category)
                                        <option value="{{$category->id}}" @if($cat_slug == $category->slug) selected @endif>{{$category->label}}</option>
                                  @endforeach
                                </select>
                            </span>
                        <span class="recurso_btn">
                                PALABRA CLAVE
                                <input type="text" name="search_text" v-on:keyup="filterResource" placeholder="Escribe algo aquí...">
                                <button v-on:click.prevent="filterResource">FILTRAR</button>
                            </span>
                    </form>
                </div>
            </div>

            <div class="col-md-4" v-if="resources.data.length > 0" v-for="(resource_info, index) in resources.data">
                <div class="service_inner">
                    <div class="service_head">
                        <h2>
                            <img v-if="resource_info.category && (resource_info.category.id == 9 || resource_info.category.id == 11 || resource_info.category.id == 12)" :src="img_path+'/images/icon/cat-icon-12.png'" alt="">
                            <img v-else-if="resource_info.category" :src="img_path+'/images/icon/cat-icon-'+resource_info.category.id+'.png'" alt="">
                            <span><a :href="base_url+'recursos?category='+resource_info.category.slug" v-text="resource_info.category.label"></a></span>
                        </h2>
                    </div>
                    <h4><a :href="base_url+'recursos/'+resource_info.slug">@{{ resource_info.title}}</a></h4>
                    <p>@{{ resource_info.review | truncate(200) }}</p>
                    <div class="author">
                        <h3>
                            <img width="45px" class="img-circle" v-if="resource_info.user.image" :src="img_path+'/uploads/'+resource_info.user.image" alt="">
                            <img width="45px" class="img-circle" v-else :src="img_path+'/images/user.png'" alt="">
                            author: <a :href="base_url+'recursos?author='+resource_info.user.username" v-text="resource_info.user.fullname || resource_info.user.username"></a>
                        </h3>
                    </div>
                    <div class="comment" :class="{'comment_red': resource_info.like.length > 0}">
                        <span>
                            <div class="dropdown">
                              <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                                  <img :src="img_path+'/images/share.png'" alt="">
                              </button>
                              <ul class="dropdown-menu addthis_toolbox">
                                <li><a  class="addthis_button_email" style="margin-left: 0px">Email</a></li>
                                <li><a  class="addthis_button_facebook" style="margin-left: 0px">Facebook</a></li>
                                <li><a  class="addthis_button_twitter" style="margin-left: 0px">Twitter</a></li>
                              </ul>
                            </div>
                            Compartir Recurso
                        </span>
                        <span style="cursor: pointer" @if($auth) v-on:click.prevent="givenResourceLike(resource_info)" @else onclick="window.location.href='ingreser?redirect=resource_list'" @endif>
                            <span v-if="resource_info.likes_count.length > 0" v-text="resource_info.likes_count[0].total"></span>
                            <span v-else>0</span>
                            <img v-if="resource_info.like.length > 0" :src="img_path+'/images/love3.png'" alt="">
                            <img v-else :src="img_path+'/images/love.jpg'" alt="">
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-12" v-if="resources.data.length == 0">
                <br><br><br>
                <div class="well">
                    <h4 class="text-center text-danger">No Resource found. Please try another filter.</h4>
                </div>
                <br><br><br>
            </div>
            <div class="col-md-12 clearfix text-center">
                <div class="service_btn" v-if="resources.data && resources.data.length > 0">
                    <a href="#" v-if="resources.next_page_url" v-on:click.prevent="getNextResources(resources.next_page_url)">ver mus recursus</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@section('scripts')
    <script>
        var search_text = '<?php $s=(isset($_GET['search_text']))?$_GET['search_text']:''; echo 'search_text='.$s;?>';
        var tag_slug = '<?php echo '&tag_slug='.$tag_slug;?>';
        var cat_slug = '<?php echo '&cat_slug='.$cat_slug;?>';
        var author = '<?php echo '&author='.$author;?>';
    </script>
    <script type="text/javascript" src="{{asset('js/resource_list.js')}}"></script>
@endsection










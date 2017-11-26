@extends('layouts.layout')
@section('styles')
    <style>
        span.has-error{
            margin-top: -22px!important;
        }
        .login_s{
            z-index:20!important;
        }
    </style>
@endsection

@section('content')

    <div class="login_content login_s" id="auth">
        <h2>Ingresa a Paralideres.org</h2>
        <div class="login_inner clearfix">
            <form id="login_form" v-on:submit.prevent="login('login_form','login')">
                <div class="input_content clearfix" :class="{'has-error':errors.email}">
                    <label>USUARIO</label>
                    <input type="text" name="email" v-on:keyup="login('login_form','')" placeholder="USUARIO">
                    <span v-if="errors.email" class="has-error" v-text="errors.email[0]"></span>
                </div>
                <div class="input_content" :class="{'has-error':errors.password}">
                    <label>CONTRASEÑA</label>
                    <input type="password"  name="password" v-on:keyup="login('login_form','')" placeholder="CONTRASEÑA">
                    <span v-if="errors.password" class="has-error" v-text="errors.password[0]"></span>
                </div>
                <p><a href="{{url('/password-reset')}}">Olvide mi contrasena</a></p>
                <button type="submit" :disabled="submitDisable">Ingresar</button>
                <span>No tengo cuenta en Paralideres.org, <a href="{{url('/registrarme')}}">Registrarme</a></span>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.redirect = '<?php if(isset($_GET['redirect'])){echo $_GET['redirect'];}else{echo '';}?>';
        window.slug = '<?php if(isset($_GET['slug'])){echo $_GET['slug'];}else{echo '';}?>';
        
        $(function(){
            setTimeout(function(){
                $('[name="email"]').focus();
            }, 2000);
        });
    </script>
@endsection

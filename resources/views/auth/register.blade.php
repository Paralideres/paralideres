@extends('layouts.layout')
@section('styles')
    <style>
        span.has-error{
            margin-top: -22px!important;
        }
    </style>
@endsection
@section('content')

    <div class="login_content" id="auth">
        <h2>Registrarme a Paralideres.org</h2>
        <div class="login_inner clearfix">
            <form id="signup_form" v-on:submit.prevent="signup('signup_form','signup')">
                <div class="input_content clearfix" :class="{'has-error':errors.email}">
                    <label>USUARIO</label>
                    <input type="text" name="email" v-on:keyup="signup('signup_form','')" placeholder="Ingresa tu email">
                    <span v-if="errors.email" class="has-error" v-text="errors.email[0]"></span>
                </div>
                <div class="input_content" :class="{'has-error':errors.password}">
                    <label>CONTRASEÑA</label>
                    <input type="password" name="password" v-on:keyup="signup('signup_form','')" placeholder="Ingresa una clave">
                    <span v-if="errors.password" class="has-error" v-text="errors.password[0]"></span>
                </div>
                <div class="input_content" :class="{'has-error':errors.password_confirmation}">
                    <label>CONTRASEÑA</label>
                    <input type="password" name="password_confirmation" v-on:keyup="signup('signup_form','')" placeholder="Confirma tu clave">
                    <span v-if="errors.password_confirmation" class="has-error" v-text="errors.password_confirmation[0]"></span>
                </div>
                <p><a href="{{url('/password-reset')}}">Olvide mi contrasena</a></p>
                <button type="submit" :disabled="submitDisable">Registrarme</button>
                <span>Tengo una cuenta en Paralideres.org, <a href="{{url('/ingreser')}}">Ingresar</a></span>

            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.redirect = '<?php if(isset($_GET['redirect'])){echo $_GET['redirect'];}else{echo '';}?>';
        window.slug = '<?php if(isset($_GET['slug'])){echo $_GET['slug'];}else{echo '';}?>';
    </script>
@endsection

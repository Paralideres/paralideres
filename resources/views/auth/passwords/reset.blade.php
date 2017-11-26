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
        <div class="login_inner clearfix">
            <h2>restablecer la contraseña a Paralideres.org</h2>
            <form id="login_form" v-on:submit.prevent="passwordReset('login_form','login')">
                <div class="input_content clearfix" :class="{'has-error':errors.email}">
                    <label>USUARIO</label>
                    <input type="text" name="email" v-on:keyup="passwordReset('login_form','')" placeholder="USUARIO">
                    <span v-if="errors.email" class="has-error" v-text="errors.email[0]"></span>
                    <span v-if="errors.emailFailed" class="has-error" v-text="errors.emailFailed"></span>
                </div>
                <div class="input_content" :class="{'has-error':errors.password}">
                    <label>CONTRASEÑA</label>
                    <input type="password" name="password" v-on:keyup="passwordReset('login_form','')" placeholder="CONTRASEÑA">
                    <span v-if="errors.password" class="has-error" v-text="errors.password[0]"></span>
                </div>
                <div class="input_content" :class="{'has-error':errors.password_confirmation}">
                    <label>CONTRASEÑA</label>
                    <input type="password" name="password_confirmation" v-on:keyup="passwordReset('login_form','')" placeholder="CONTRASEÑA">
                    <span v-if="errors.password_confirmation" class="has-error" v-text="errors.password_confirmation[0]"></span>
                </div>
                <button type="submit">restablecer la contraseña</button>
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


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
        <h2>Olvide mi contrasena a Paralideres.org</h2>
        <div class="login_inner clearfix">
            <form id="login_form" v-on:submit.prevent="passwordResetEmail('login_form','login')">
                <div class="input_content clearfix" :class="{'has-error':errors.email}">
                    <label>USUARIO</label>
                    <input type="text" name="email" v-on:keyup="passwordResetEmail('login_form','')" placeholder="USUARIO">
                    <span v-if="errors.email" class="has-error" v-text="errors.email[0]"></span>
                    <span v-if="errors.emailFailed" class="has-error" v-text="errors.emailFailed"></span>
                </div>
                <button type="submit">enviar enlace de restablecimiento de contraseña</button>
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


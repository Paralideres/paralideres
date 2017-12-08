@extends('layouts.layout')
@section('styles')
    <style>
        span.has-error {
            margin-top: -22px !important;
        }

        .login_s {
            z-index: 20 !important;
        }
    </style>
@endsection

@section('content')

    <div class="login_content login_s" id="auth">
        <h2>Ingresa a Paralideres.org</h2>
        <div class="login_inner removeVh clearfix">
            @if(isset($data) && $data['status'] == 2000)
                <p class="text-center">{{$data['msg']}}</p>
                <br><br>
                <form id="login_form" v-on:submit.prevent="login('login_form','login')">
                    <div class="input_content clearfix" :class="{'has-error':errors.email}">
                        <label>USUARIO</label>
                        <input type="text" name="email" v-on:keyup="login('login_form','')" placeholder="USUARIO"
                               class="noMargin">
                        <p class="custom-err-msg">
                            <span v-if="errors.email" v-text="errors.email[0]"></span>
                        </p>
                    </div>
                    <div class="input_content" :class="{'has-error':passcheck.error}">
                        <label>CONTRASEÑA</label>
                        <input type="password" v-on:keyup="checkPass" name="password" placeholder="CONTRASEÑA"
                               class="noMargin">
                        <p class="custom-err-msg">
                            <span v-if="errors.password" v-text="errors.password[0]"></span>
                        </p>
                    </div>
                    <p><a href="{{url('/password-reset')}}">Olvide mi contrasena</a></p>
                    <button type="submit">Ingresar</button>
                    <span>No tengo cuenta en Paralideres.org, <a href="{{url('/registrarme')}}">Registrarme</a></span>
                </form>
            @elseif(isset($data) && $data['status'] == 5000)
                <h4 class="text-center text-danger">{{$data['msg']}}</h4>
            @else
                <form id="login_form" v-on:submit.prevent="login('login_form','login')">
                    <div class="input_content clearfix" :class="{'has-error':errors.email}">
                        <label>USUARIO</label>
                        <input type="text" name="email" v-on:keyup="getValid('email')" placeholder="USUARIO"
                               class="noMargin">
                        <p class="custom-err-msg">
                            <span v-if="errors.email" v-text="errors.email[0]"></span>
                        </p>
                    </div>
                    <div class="input_content" :class="{'has-error':errors.password}">
                        <label>CONTRASEÑA</label>
                        <input type="password" v-on:keyup="getValid('password')" name="password" placeholder="CONTRASEÑA"
                               class="noMargin">
                        <p class="custom-err-msg">
                            <span v-if="errors.password" v-text="errors.password[0]"></span>
                        </p>
                    </div>
                    <p><a href="{{url('/password-reset')}}">Olvide mi contrasena</a></p>
                    <button type="submit">Ingresar</button>
                    <span>No tengo cuenta en Paralideres.org, <a href="{{url('/registrarme')}}">Registrarme</a></span>
                </form>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.redirect = '<?php if (isset($_GET['redirect'])) {
            echo $_GET['redirect'];
        } else {
            echo '';
        }?>';
        window.slug = '<?php if (isset($_GET['slug'])) {
            echo $_GET['slug'];
        } else {
            echo '';
        }?>';

        $(function () {
            setTimeout(function () {
                $('[name="email"]').focus();
            }, 2000);
        });
    </script>
@endsection

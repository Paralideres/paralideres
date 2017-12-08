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
        <h2>Registro exitoso</h2>
        <div class="login_inner removeVh clearfix">
            <h3 class="text-center text-info">
                {{--Please check your mail for activation link and activate your account--}}
                Por favor revise su correo para ver el enlace de activación y active su cuenta
            </h3>
            <br><br><br><br>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        window.redirect = '<?php if(isset($_GET['redirect'])){echo $_GET['redirect'];}else{echo '';}?>';
        window.slug = '<?php if(isset($_GET['slug'])){echo $_GET['slug'];}else{echo '';}?>';
    </script>
@endsection

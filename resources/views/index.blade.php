<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta  name="viewport"  content="width=width-width, initial-scale=1, slim-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>crpKey</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <!-- Load required Bootstrap and BootstrapVue CSS -->
    <link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap/dist/css/bootstrap.min.css" />
    <link type="text/css" rel="stylesheet" href="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.css" />

    <!-- Load polyfills to support older browsers -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es2015%2CIntersectionObserver" crossorigin="anonymous"></script>

    <!-- Load Vue followed by BootstrapVue -->
    {{-- <script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue.min.js"></script> --}}

    <!-- Load the following for BootstrapVueIcons support -->
    {{-- <script src="https://unpkg.com/bootstrap-vue@latest/dist/bootstrap-vue-icons.min.js"></script> --}}
</head>
<body>

    <div id="app">
        <app></app>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
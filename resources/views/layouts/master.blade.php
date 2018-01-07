
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
<!-- CSS -->
<link rel="stylesheet" href="{{asset('css/app.css')}}">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition {{(Auth::user()->type() == 'donor') ? 'skin-red' : 'skin-blue'}} sidebar-mini">
<!-- Site wrapper -->
<div id="app" class="wrapper">
@include('layouts.elements.header')
  <!-- =============================================== -->
@include('layouts.elements.nav-left')
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


@yield('content')

  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Верзија</b> {{config('app.version')}}
    </div>
    <strong>Copyright &copy; {{date('Y')}} <a href="https://ajdemakedonija.mk">Ајде Македонија</a>.</strong> Сите права се задржани.
  </footer>

{{-- @include('layouts.elements.control-sidebar') --}}

</div>
<!-- ./wrapper -->
<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
<!-- test js, TODO remove when done! -->
<script type="text/javascript" src="{{asset('js/test.js')}}"></script>
</body>
</html>

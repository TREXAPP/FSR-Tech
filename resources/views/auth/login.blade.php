@extends('layouts.home')

@section('content')

<div class="home-logo">
  <img class="center-block" src="{{ url('img/logo.png')}}" >
</div>
<div class="home-nav-bar">
  <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                  </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li><a href="#" target="_blank"><span>ДОНАТОРИ</span></a></li>
          <li><a href="#" target="_blank"><span>ПРИМАТЕЛИ</span></a></li>
          <li><a href="#" target="_blank"><span>ДОСТАВУВАЧИ</span></a></li>
          <li><a href="#" target="_blank"><span>ЧЛЕНОВИ</span></a></li>
          <li><a href="#" target="_blank"><span>ЧЕСТО ПОСТАВУВАНИ ПРАШАЊА</span></a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>
<div class="main-image image-wrapper col-xs-12">
  <img src="{{url("img/home_image_main.png")}}" >
</div>
<div class="secondary-images col-xs-12">
  <div class="secondary-image image-wrapper secondary-image-1 col-sm-6">
    <img src="{{url("img/home_image_left.png")}}" >
  </div>
  <div class="secondary-image image-wrapper secondary-image-2 col-sm-6">
    <img src="{{url("img/home_image_right.png")}}" >
  </div>
</div>
<div class="bottom-panel col-xs-10 col-xs-offset-1">
  <div class="bottom-panel-text-1 col-md-4">
    <div class="text-wrapper">
      <div class="text-headline">
        ВКЛУЧИ СЕ И ТИ!
      </div>
      <div class="text-body">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="bottom-panel-image-2 col-sm-4 circle-container">
      <div class="circle circle-red">
          <p>Донатори</p>
      </div>
    </div>
    <div class="bottom-panel-image-3 col-sm-4 circle-container">
      <div class="circle circle-green">
          <p>Приматели</p>
      </div>
    </div>
    <div class="bottom-panel-image-4 col-sm-4 circle-container">
      <div class="circle circle-white">
          <p>Доставувачи</p>
      </div>
    </div>
  </div>
</div>

@endsection

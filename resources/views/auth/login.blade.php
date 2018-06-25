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
          <li><a href="http://ajdemakedonija.mk/mk/business-contact-form-mk/" target="_blank"><span>ДОНАТОРИ</span></a></li>
          <li><a href="http://wp.ajdemakedonija.mk/cso-contact-form/" target="_blank"><span>ПРИМАТЕЛИ</span></a></li>
          <li><a href="https://drive.google.com/open?id=1zi2coW0qLhpc74JiVjliYlSV_Z_nsQ71Bvta-tsXM3U" target="_blank"><span>ДОСТАВУВАЧИ</span></a></li>
          <li><a href="https://drive.google.com/open?id=1OiP5Jo-F43MhqGuDxvGVXyJO0gJy5KGLhCkKFA3AQyM" target="_blank"><span>ЧЛЕНОВИ</span></a></li>
          <li><a href="https://drive.google.com/open?id=1fClgnHS2QMeA18nbjKg1z_DrYhkmDYy20r_JV1BvgeU" target="_blank"><span>ЧЕСТО ПОСТАВУВАНИ ПРАШАЊА</span></a></li>
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
    <a href="http://ajdemakedonija.mk/mk/food-waste-food-insecurity-in-macedonia-mk/" target="_blank"><img src="{{url("img/home_image_left.png")}}" ></a>
  </div>
  <div class="secondary-image image-wrapper secondary-image-2 col-sm-6">
    <a href="http://ajdemakedonija.mk/mk/how-can-you-help-%D0%BC%D0%BA/" target="_blank"><img src="{{url("img/home_image_right.png")}}" ></a>
  </div>
</div>
<div class="bottom-panel col-xs-10 col-xs-offset-1">
  <div class="bottom-panel-text-1 col-md-4">
    <a href="http://ajdemakedonija.mk/mk/how-can-you-help-%D0%BC%D0%BA/" target="_blank">
      <div class="text-wrapper">
        <div class="text-headline">
          ВКЛУЧИ СЕ И ТИ!
        </div>
        {{-- <div class="text-body">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
        </div> --}}
      </div>
    </a>
  </div>
  <div class="col-md-8">
    <a href="http://ajdemakedonija.mk/mk/business-contact-form-mk/">
      <div class="bottom-panel-image-2 col-sm-4 circle-container">
        <div class="circle circle-red">
            <p>Донатори</p>
        </div>
      </div>
    </a>

    <a href="http://wp.ajdemakedonija.mk/cso-contact-form/">
      <div class="bottom-panel-image-3 col-sm-4 circle-container">
        <div class="circle circle-green">
            <p>Приматели</p>
        </div>
      </div>
    </a>

<a href="https://drive.google.com/open?id=1zi2coW0qLhpc74JiVjliYlSV_Z_nsQ71Bvta-tsXM3U">
  <div class="bottom-panel-image-4 col-sm-4 circle-container">
    <div class="circle circle-white">
        <p>Доставувачи</p>
    </div>
  </div>
</a>

  </div>
</div>

@endsection

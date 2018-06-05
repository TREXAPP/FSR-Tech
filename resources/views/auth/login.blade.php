@extends('layouts.home')

@section('content')
  {{-- <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
          <div class="navbar-header">

              <!-- Collapsed Hamburger -->
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                  <span class="sr-only">@lang('app.toggle')</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>

              <!-- Branding Image -->

              <a class="navbar-brand" href="{{ url('/') }}">
                  <img class="logo-home-img" src="{{url('img/logo.png')}}" alt="">
                  {{ config('app.name', 'Laravel') }}
              </a>
          </div>

          <div class="collapse navbar-collapse" id="app-navbar-collapse">
              <!-- Left Side Of Navbar -->
              <ul class="nav navbar-nav">
                  &nbsp;
              </ul>

              <!-- Right Side Of Navbar -->
              <ul class="nav navbar-nav navbar-right">
                  <!-- Authentication Links -->
                  @guest
                      <li><a href="{{ route('login') }}">@lang('app.login')</a></li>
                      <li><a href="{{ route('register') }}">@lang('app.register')</a></li>
                    @else
                      <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                              {{ Auth::user()->email }} <span class="caret"></span>
                          </a>

                          <ul class="dropdown-menu">
                              <li>
                                  <a href="{{ route('logout') }}"
                                      onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                      Logout
                                  </a>

                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                      {{ csrf_field() }}
                                  </form>
                              </li>
                          </ul>
                      </li>
                  @endguest
              </ul>
          </div>
      </div>
  </nav>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">@lang('login.login')</div>

                <div class="panel-body">
                  @if (session('status'))
                      <div class="alert alert-success">
                          {{ session('status') }}
                      </div>
                  @endif
                  @if (session('status_error'))
                      <div class="alert alert-danger">
                          {{ session('status_error') }}
                      </div>
                  @endif

                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">@lang('login.email')</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">@lang('login.password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('login.remember')
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('login.login')
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    @lang('login.forgot')
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="top-bar">
  <div class="top-bar-elements pull-right">
    <div class="top-bar-contact-email top-bar-element">
      <a href="mailto:sitesitimk@gmail.com"><i class="fa fa-envelope"></i>sitesitimk@gmail.com</a>
    </div>
    <div class="top-bar-contact-phone top-bar-element">
      <i class="fa fa-phone"></i>
      +389 078 259 528
    </div>
    <div class="top-bar-register top-bar-element">
      <a href="{{route("register")}}">Регистрирај се</a>
    </div>
    <div class="top-bar-login top-bar-element dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-user"></i>Најави се
        </a>
        <ul class="dropdown-menu login-dropdown-menu">
        <form class="form-horizontal login-dropdown-form" method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <div class="col-xs-12">
                    <input id="email" type="email" class="form-control"
                          name="email" value="{{ old('email') }}"
                          placeholder="@lang('login.email')" required autofocus>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <div class="col-xs-12">
                    <input id="password" type="password"
                    class="form-control" name="password"
                    placeholder="@lang('login.password')" required>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-xs-12">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> @lang('login.remember')
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group login-submit-group">
              <div class="col-xs-12">
                <button type="submit" class="btn btn-primary col-xs-12">
                    @lang('login.login')
                </button>
              </div>
            </div>

            <div class="col-xs-12">
              <a class="btn btn-link" href="{{ route('password.request') }}">
                  @lang('login.forgot')
              </a>
            </div>
        </form>
      </ul>
    </div>
  </div>
</div>
<div class="errors">
  @if (!$errors->isEmpty())
    @foreach ($errors->all() as $error)
      <div class="alert alert-danger">
        {{$error}}
      </div>
    @endforeach
  @endif
  @if (session('status'))
  <div class="alert alert-success">
    {{ session('status') }}
  </div>
  @endif
  @if (session('status_error'))
  <div class="alert alert-danger">
    {{ session('status_error') }}
  </div>
  @endif
</div>
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
<div class="footer">
  <div class="logos-left col-md-10">
    <div class="logos-left-wrapper col-md-12">
      <div class="footer-logo logo-ajdemakedonija col-sm-3 col-xs-6">
        <img src="{{url("img/logo_ajde_makedonija.png")}}" alt="Ајде Македонија">
      </div>
      <div class="footer-logo logo-swiss col-sm-3 col-xs-6">
        <img src="{{url("img/logo_swiss.png")}}" alt="Swiss Embassy">
      </div>
      <div class="footer-logo logo-peacecorps col-sm-3 col-xs-6">
        <img src="{{url("img/logo_peace_corps.png")}}" alt="Peace Corps">
      </div>
      <div class="footer-logo logo-usaid col-sm-3 col-xs-6">
        <img src="{{url("img/logo_USAID.png")}}" alt="USAID">
      </div>
    </div>
  </div>
  <div class="social-right col-md-2">
    <div class="footer-logo social-fb col-md-6 col-md-offset-0 col-sm-2 col-sm-offset-4 col-xs-3 col-xs-offset-3">
      <a href="https://www.facebook.com/LetsdoitMacedonia/" target="_blank"><img src="{{url("img/social_fb.png")}}" alt="Facebook"></a>
    </div>
    <div class="social-twitter footer-logo col-md-6 col-sm-2 col-xs-3">
      <a href="https://twitter.com/AjdeMakedonija" target="_blank"><img src="{{url("img/social_twitter.png")}}" alt="Twitter"></a>
    </div>
  </div>
</div>
@endsection

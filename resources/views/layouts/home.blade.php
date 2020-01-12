<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.15.5/bootstrap-table.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.15.5/bootstrap-table.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="home-body">

    <div id="app">
      <div class="top-bar">
        <div class="top-bar-logo pull-left">
          <a href="{{route('home')}}">
            <img class="center-block" src="{{ url('img/logo.png')}}" >
          </a>
        </div>
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
                          <input id="email-input" type="email" class="form-control"
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
            <div class="alert alert-danger home-alert">
              {{$error}}
            </div>
          @endforeach
        @endif
        @if (session('status'))
        <div class="alert alert-success home-alert">
          {{ session('status') }}
        </div>
        @endif
        @if (session('status_error'))
        <div class="alert alert-danger home-alert">
          {{ session('status_error') }}
        </div>
        @endif
      </div>
        @yield('content')
      <div class="footer">
        <div class="logos-left col-md-9">
          <div class="logos-left-wrapper col-md-12">
            <div class="footer-logo logo-ajdemakedonija col-sm-3 col-xs-6">
              <a href="http://ajdemakedonija.sitesiti.mk/">
                <img src="{{url("img/logo_ajde_makedonija.png")}}" alt="Ајде Македонија">
              </a>
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
        <div class="social-right col-md-3">
          <div class="footer-logo social-fb col-md-4 col-md-offset-0 col-sm-2 col-sm-offset-3 col-xs-3 col-xs-offset-1">
            <a href="https://www.facebook.com/LetsdoitMacedonia/" target="_blank"><img src="{{url("img/social_fb.png")}}" alt="Facebook"></a>
          </div>
          <div class="social-twitter footer-logo col-md-4 col-sm-2 col-xs-3 ">
            <a href="https://twitter.com/AjdeMakedonija" target="_blank"><img src="{{url("img/social_twitter.png")}}" alt="Twitter"></a>
          </div>
          <div class="social-instagram footer-logo col-md-4 col-sm-2 col-xs-3">
            <a href="https://www.instagram.com/ajdemakedonija/" target="_blank"><img src="{{url("img/social_instagram.png")}}" alt="Instagram"></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
</body>
</html>

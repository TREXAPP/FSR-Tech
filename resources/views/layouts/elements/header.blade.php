<header class="main-header">
  <!-- Logo -->
  <a href="/" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <img class="logo-mini" src="{{url('img/logo.png')}}" />
    {{--<span class="logo-mini">{{ config('app.name', 'Laravel') }}</span>--}}
    <!-- logo for regular state and mobile devices -->
    <img class="logo-lg" src="{{url('img/logo.png')}}" />
  <span class="logo-lg">{{ config('app.name', 'Laravel') }}</span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        {{-- <li class="dropdown messages-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-commenting-o"></i>
            <span class="label label-success">4</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Имате 4 непрочитани пораки</li>
            <li>
              <!-- inner menu: contains the actual data -->
              <!-- TODO messages here -->
              <ul class="menu">
                <li><!-- start message -->
                  <a href="#">
                    <div class="pull-left">
                      <img src="{{url('img/user1-128x128.jpg')}}" class="img-rounded" alt="User Image">
                    </div>
                    <h4>
                      Игор Пирковски
                      <small><i class="fa fa-clock-o"></i> 5 мин</small>
                    </h4>
                    <p>Ќе дојдам за 15 мин!</p>
                  </a>
                </li>
                <!-- end message -->
              </ul>
            </li>
            <li class="footer"><a href="#">Сите пораки</a></li>
          </ul>
        </li> --}}
        <!-- Notifications: style can be found in dropdown.less -->
        {{-- <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">10</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Имате 10 нови известувања</li>
            <li>
              <!-- inner menu: contains the actual data -->
              <ul class="menu">
                <li>
                  <a href="#">
                    <i class="fa fa-users text-aqua"></i> Вашата донација е прифатена!
                  </a>
                </li>
              </ul>
            </li>
            <li class="footer"><a href="#">Сите известувања</a></li>
          </ul>
        </li> --}}


        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{Methods::get_user_image_url(Auth::user())}}" class="user-image" alt="User Image">
            <span class="hidden-xs">{{Auth::user()->email}}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
                <img src="{{Methods::get_user_image_url(Auth::user())}}" class="img-rounded" alt="User Image">
              <p> {{Auth::user()->first_name}} {{Auth::user()->last_name}}<br>
                  {{Auth::user()->organization->name}}
                <small>{{Auth::user()->phone}}</small>
                <small>{{Auth::user()->address}}</small>
              </p>
            </li>

            </li>
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="/{{Auth::user()->type()}}/profile" class="btn btn-default btn-flat">Профил</a>
              </div>
              <div class="pull-right">
                <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">Одјави се</a>
                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                     {{ csrf_field() }}
                 </form>
              </div>
            </li>
          </ul>
        </li>
        <!-- Control Sidebar Toggle Button -->
        {{-- <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li> --}}
      </ul>
    </div>
  </nav>
</header>

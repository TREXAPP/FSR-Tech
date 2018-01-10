<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if (Auth::user()->profile_image_id == null)
          <img src="{{url('img/avatar5.png')}}" class="img-rounded" alt="User Image">
        @elseif (FSR\File::find(Auth::user()->profile_image_id)->filename == null)
          <img src="{{url('img/avatar5.png')}}" class="img-rounded" alt="User Image">
        @else
          <img src="{{FSR\Custom\Methods::getFileUrl(FSR\File::find(Auth::user()->profile_image_id)->filename)}}" class="img-rounded" alt="User Image">
        @endif
      </div>
      <div class="pull-left info master-info">
        <p>
          <small>
            <span>{{Auth::user()->first_name}}</span><span>{{Auth::user()->last_name}}</span>
          </small>
        </p>
      </div>
    </div>

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">


      {{-- <li>
        <a href="/{{Auth::user()->type()}}/home">
          <i class="fa fa-dashboard"></i> <span>Почетна</span>
          <span class="pull-right-container">
          </span>
        </a>
      </li> --}}

      {{-- <li class="header">ДОНАЦИИ</li> --}}


      <li>
        <a href="{{route('admin.approve_users')}}">
          <i class="fa fa-bookmark"></i> <span>Одобри корисници</span>
          <span class="pull-right-container">
            {{-- <small class="label pull-right bg-blue">2</small> --}}
          </span>
        </a>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Корисници</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Приматели</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers/new">
                <i class="fa fa-user-plus"></i> <span>Донори</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Организации</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Додади нова</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Приматели</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers/new">
                <i class="fa fa-user-plus"></i> <span>Донори</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Храна</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Категории</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Производи</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers/new">
                <i class="fa fa-user-plus"></i> <span>Количини</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Известувања</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Пораки</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Нови известувања</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Подесувања</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="inactive-menu">
              <a href="/{{Auth::user()->type()}}/profile">
                <i class="fa fa-user-circle"></i> <span>Мој профил</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="#">
                <i class="fa fa-shield"></i> <span>Безбедност</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li class="inactive-menu">
              <a href="#">
                <i class="fa fa-exchange"></i> <span>Подеси известувања</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>
          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Извештаи</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="inactive-menu">
              <a href="#">
                <i class="fa fa-star-half-full"></i> <span>Рејтинзи</span>
                <span class="pull-right-container">
                  {{-- <small class="label pull-right bg-green">4.74</small> --}}
                </span>
              </a>
            </li>
            <li class="inactive-menu">
              <a href="#">
                <i class="fa fa-th"></i> <span>Статистики</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
          <i class="fa fa-sign-out"></i> <span>Одјави се</span>
          <span class="pull-right-container">
          </span>
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
      </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

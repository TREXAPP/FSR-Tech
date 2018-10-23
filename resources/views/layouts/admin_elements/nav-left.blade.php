<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if (Auth::user()->profile_image_id == null)
          <img src="{{url('img/admin.png')}}" class="img-rounded" alt="User Image">
        @elseif (FSR\File::find(Auth::user()->profile_image_id)->filename == null)
          <img src="{{url('img/admin.png')}}" class="img-rounded" alt="User Image">
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

      <li>
        <a href="{{route('admin.listings')}}">
          <i class="fa fa-bookmark"></i> <span>Донации</span>
          <span class="pull-right-container">
            {{-- <small class="label pull-right bg-blue">2</small> --}}
          </span>
        </a>
      </li>

      <li>
        <a href="{{route('admin.email')}}">
          <i class="fa fa-bookmark"></i> <span>Прати емаил</span>
          <span class="pull-right-container">
            {{-- <small class="label pull-right bg-blue">2</small> --}}
          </span>
        </a>
      </li>
      @if (Auth::guard('master_admin')->user())
      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Администратори</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="{{route('master_admin.admins')}}">
                <i class="fa fa-users"></i> <span>Преглед</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="{{route('master_admin.new_admin')}}">
                <i class="fa fa-user-plus"></i> <span>Додади нов</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>
      @endif
      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Корисници</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="{{route('admin.cso_users')}}">
                <i class="fa fa-users"></i> <span>Приматели</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="{{route('admin.donor_users')}}">
                <i class="fa fa-user-plus"></i> <span>Донатори</span>
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
            <li>
              <a href="{{route('admin.new_organization')}}">
                <i class="fa fa-users"></i> <span>Додади нова</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="{{route('admin.cso_organizations')}}">
                <i class="fa fa-users"></i> <span>Приматели</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="{{route('admin.donor_organizations')}}">
                <i class="fa fa-user-plus"></i> <span>Донатори</span>
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

            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Категории
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{route('admin.food_types')}}"><i class="fa fa-circle-o"></i> Преглед</a></li>
                <li><a href="{{route('admin.new_food_type')}}"><i class="fa fa-circle-o"></i> Додади нова</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Производи
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{route('admin.products')}}"><i class="fa fa-circle-o"></i> Преглед</a></li>
                <li><a href="{{route('admin.new_product')}}"><i class="fa fa-circle-o"></i> Додади нов</a></li>
              </ul>
            </li>

            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Количини
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="{{route('admin.quantity_types')}}"><i class="fa fa-circle-o"></i> Преглед</a></li>
                <li><a href="{{route('admin.new_quantity_type')}}"><i class="fa fa-circle-o"></i> Додади нова</a></li>
              </ul>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Доставувачи</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="/{{Auth::user()->type()}}/volunteers">
                <i class="fa fa-users"></i> <span>Преглед</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="/{{Auth::user()->type()}}/volunteers/new">
                <i class="fa fa-user-plus"></i> <span>Додади нов</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>


            <li class="treeview">
                <a href="#">
                  <i class="fa fa-universal-access"></i> <span>Типови на транспорт</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li>
                    <a href="/{{Auth::user()->type()}}/transport_types">
                      <i class="fa fa-users"></i> <span>Преглед</span>
                      <span class="pull-right-container">
                      </span>
                    </a>
                  </li>

                  <li>
                    <a href="/{{Auth::user()->type()}}/transport_types/new">
                      <i class="fa fa-user-plus"></i> <span>Додади нов</span>
                      <span class="pull-right-container">
                      </span>
                    </a>
                  </li>

                </ul>
            </li>


      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Типови на донатори</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="/{{Auth::user()->type()}}/donor_types">
                <i class="fa fa-users"></i> <span>Преглед</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="/{{Auth::user()->type()}}/donor_types/new">
                <i class="fa fa-user-plus"></i> <span>Додади нов</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-universal-access"></i> <span>Локации</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="{{route('admin.locations')}}">
                <i class="fa fa-users"></i> <span>Преглед</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="/{{Auth::user()->type()}}/locations/new">
                <i class="fa fa-user-plus"></i> <span>Додади нова</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

          </ul>
      </li>

      <li class="treeview">
          <a href="#">
            <i class="fa fa-bookmark"></i> <span>Ресурси</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li>
              <a href="{{route('admin.resource_page_donor')}}">
                <i class="fa fa-user-circle"></i> <span>Донатор</span>
                <span class="pull-right-container">
                </span>
              </a>
            </li>

            <li>
              <a href="{{route('admin.resource_page_cso')}}">
                <i class="fa fa-user-circle"></i> <span>Примател</span>
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
            <li>
              <a href="{{route('admin.donations_report')}}">
                <i class="fa fa-star-half-full"></i> <span>Донации</span>
                <span class="pull-right-container">
                  {{-- <small class="label pull-right bg-green">4.74</small> --}}
                </span>
              </a>
            </li>
            <li>
              <a href="{{route('admin.product_donations_report')}}">
                <i class="fa fa-star-half-full"></i> <span>Донации по производ</span>
                <span class="pull-right-container">
                  {{-- <small class="label pull-right bg-green">4.74</small> --}}
                </span>
              </a>
            </li>
            <li>
              <a href="{{route('admin.activity_report')}}">
                <i class="fa fa-star-half-full"></i> <span>Активност</span>
                <span class="pull-right-container">
                  {{-- <small class="label pull-right bg-green">4.74</small> --}}
                </span>
              </a>
            </li>
            <li>
              <a href="{{route('admin.registration_report')}}">
                <i class="fa fa-th"></i> <span>Регистрации</span>
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

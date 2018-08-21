@extends('layouts.admin_master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header activity-report-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Извештај за активност</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/admin/home"> Admin</a></li>
      <li><a href="#"><i class="fa fa-cutlery"></i> Извештаи</a></li>
      <li><a href="{{route('admin.activity_report')}}"><i class="fa fa-cutlery"></i> Активност</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content activity-report-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


  <!-- Filter -->
  <section class="filter activity-report-filter">
  	<div class="filter-wrapper row">
    	<form id="activity-report-filter-form" class="activity-report-filter-form" action="{{route('admin.activity_report')}}" method="post">
    		<input type="hidden" name="post-type" value="filter" />
    		{{csrf_field()}}
    		<div class="filter-container">
    			{{-- <div class="filter-label activity-report-filter-label col-md-4">
    				<label for="activity-report-filter-select">Организација:</label>
    			</div> --}}


          <div class="filter_date_from_wrapper form-group col-md-3">
            <div class="filter_label_wrapper col-xs-2">
              <label for="filter_date_from">Од:</label>
            </div>
            <div class="filter_input_wrapper col-xs-10">
              <input id="filter_date_from" type="date" class="form-control" name="filter_date_from" value="{{$date_from}}"/>
            </div>
          </div>
          <div class="filter_date_to_wrapper form-group col-md-3">
            <div class="filter_label_wrapper col-xs-2">
              <label for="filter_date_to">До:</label>
            </div>
            <div class="filter_input_wrapper col-xs-10">
              <input id="filter_date_to" type="date" class="form-control" name="filter_date_to" value="{{$date_to}}"/>
            </div>
          </div>

          <div class="filter_submit_wrapper form-group col-md-2">
            <button type="submit" class="btn btn-primary col-xs-12">Филтрирај</button>
          </div>

          @if ($errors->has('activity-report-filter-select'))
            <span class="help-block">
              <strong>{{ $errors->first('activity-report-filter-select') }}</strong>
            </span>
          @endif
    		</div>

      </form>
    </div>
  </section>

<!-- Donors table -->
<div id="availability_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Донатори</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th rowspan={{$donors->count() + $empty_donor_organizations_count + 1}}>
            <!-- Empty -->
          </th>
          <th>Организација</th>
          <th>Број на логирања по организација</th>
          <th>Посети на почетна страна по организација</th>
          <th>Корисник</th>
          <th>Број на логирања по корисник</th>
          <th>Посети на почетна страна по корисник</th>
        </tr>
        <?php $current_organization_id = 0; ?>
        @foreach ($donors as $donor)
          <tr>
            @if ($current_organization_id != $donor->organization_id)
              <?php
                $current_organization_id = $donor->organization_id;
                $donors_count = $donor->organization->donors->where('status','active')->count();
                $organization_logins = $donor->organization->donor_logs->where('user_type','donor')
                      ->where('event','login')
                      ->where('created_at', '>=', $date_from)
                      ->where('created_at', '<=', $date_to)
                      ->count();
                $organization_open_home_pages = $donor->organization->donor_logs->where('user_type','donor')
                      ->where('event','open_home_page')
                      ->where('created_at', '>=', $date_from)
                      ->where('created_at', '<=', $date_to)
                      ->count();
              ?>
              <td {{($donors_count > 1) ? 'rowspan=' . $donors_count : ''}}>{{$donor->organization->name}}</td>
              <td {{($donors_count > 1) ? 'rowspan=' . $donors_count : ''}}>{{$organization_logins}}</td>
              <td {{($donors_count > 1) ? 'rowspan=' . $donors_count : ''}}>{{$organization_open_home_pages}}</td>
            @endif
            <td>{{$donor->first_name . ' ' . $donor->last_name}}</td>
            {{-- {{dump($donor->id)}} --}}
            <td>{{FSR\Log::where('event', 'login')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to)
                             ->where('user_type','donor')
                             ->where('user_id', $donor->id)
                             ->count()}}</td>
            <td>{{FSR\Log::where('event', 'open_home_page')
                         ->where('created_at', '>=', $date_from)
                         ->where('created_at', '<=', $date_to)
                         ->where('user_type','donor')
                         ->where('user_id', $donor->id)
                         ->count()}}</td>
          </tr>
        @endforeach
        @foreach ($donor_organizations as $organization)
          @if ($organization->donors->where('status','active')->count() == 0)
            <tr>
              <td>{{$organization->name}}</td>
              <td colspan="5">Организацијата нема корисници</td>
            </tr>
          @endif
        @endforeach
          <tr>
            <th>Вкупно</th>
            <th>{{$donor_organizations->count()}}</th>
            <th>{{FSR\Log::where('event', 'login')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to)
                             ->where('user_type','donor')
                             ->count()}}</th>
            <th>{{FSR\Log::where('event', 'open_home_page')
                              ->where('created_at', '>=', $date_from)
                              ->where('created_at', '<=', $date_to)
                              ->where('user_type','donor')
                              ->count()}}</th>
            <th>{{$donors->count()}}</th>
            <th>{{FSR\Log::where('event', 'login')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to)
                             ->where('user_type','donor')
                             ->count()}}</th>
            <th>{{FSR\Log::where('event', 'open_home_page')
                              ->where('created_at', '>=', $date_from)
                              ->where('created_at', '<=', $date_to)
                              ->where('user_type','donor')
                              ->count()}}</th>
          </tr>
          {{-- @foreach ($timeframes_rows as $timeframes_row)
          <tr>
            <td>
              {{$timeframes_row->day}}
            </td>
            @foreach (FSR\Timeframe::where('status', 'active')->orderby('hours_from', 'ASC')->where('day', $timeframes_row->day)->get() as $timeframe)
              <td>
                <input id="chk_availability_{{$timeframe->id}}" name="chk_availability_{{$timeframe->id}}" type="checkbox" />
              </td>
            @endforeach
          </tr>
          @endforeach --}}

      </table>
    </div>
  </div>
</div>

<!-- Csos table -->
<div id="availability_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Приматели</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th rowspan={{$csos->count() + $empty_cso_organizations_count + 1}}>
            <!-- Empty -->
          </th>
          <th>Организација</th>
          <th>Број на логирања по организација</th>
          <th>Посети на почетна страна по организација</th>
          <th>Корисник</th>
          <th>Број на логирања по корисник</th>
          <th>Посети на почетна страна по корисник</th>
        </tr>
        <?php $current_organization_id = 0; ?>
        @foreach ($csos as $cso)
          <tr>
            @if ($current_organization_id != $cso->organization_id)
              <?php
                $current_organization_id = $cso->organization_id;
                $csos_count = $cso->organization->csos->where('status','active')->count();
                $organization_logins = $cso->organization->cso_logs->where('user_type','cso')
                      ->where('event','login')
                      ->where('created_at', '>=', $date_from)
                      ->where('created_at', '<=', $date_to)
                      ->count();
                $organization_open_home_pages = $cso->organization->cso_logs->where('user_type','cso')
                      ->where('event','open_home_page')
                      ->where('created_at', '>=', $date_from)
                      ->where('created_at', '<=', $date_to)
                      ->count();
              ?>
              <td {{($csos_count > 1) ? 'rowspan=' . $csos_count : ''}}>{{$cso->organization->name}}</td>
              <td {{($csos_count > 1) ? 'rowspan=' . $csos_count : ''}}>{{$organization_logins}}</td>
              <td {{($csos_count > 1) ? 'rowspan=' . $csos_count : ''}}>{{$organization_open_home_pages}}</td>
            @endif
            <td>{{$cso->first_name . ' ' . $cso->last_name}}</td>
            {{-- {{dump($donor->id)}} --}}
            <td>{{FSR\Log::where('event', 'login')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to)
                             ->where('user_type','cso')
                             ->where('user_id', $cso->id)
                             ->count()}}</td>
            <td>{{FSR\Log::where('event', 'open_home_page')
                         ->where('created_at', '>=', $date_from)
                         ->where('created_at', '<=', $date_to)
                         ->where('user_type','cso')
                         ->where('user_id', $cso->id)
                         ->count()}}</td>
          </tr>
        @endforeach

      @foreach ($cso_organizations as $organization)
        @if ($organization->csos->where('status','active')->count() == 0)
          <tr>
            <td>{{$organization->name}}</td>
            <td colspan="5">Организацијата нема корисници</td>
          </tr>
        @endif
      @endforeach
          <tr>
            <th>Вкупно</th>
            <th>{{$cso_organizations->count()}}</th>
            <th>{{FSR\Log::where('event', 'login')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to)
                             ->where('user_type','cso')
                             ->count()}}</th>
            <th>{{FSR\Log::where('event', 'open_home_page')
                              ->where('created_at', '>=', $date_from)
                              ->where('created_at', '<=', $date_to)
                              ->where('user_type','cso')
                              ->count()}}</th>
            <th>{{$csos->count()}}</th>
            <th>{{FSR\Log::where('event', 'login')
                             ->where('created_at', '>=', $date_from)
                             ->where('created_at', '<=', $date_to)
                             ->where('user_type','cso')
                             ->count()}}</th>
            <th>{{FSR\Log::where('event', 'open_home_page')
                              ->where('created_at', '>=', $date_from)
                              ->where('created_at', '<=', $date_to)
                              ->where('user_type','cso')
                              ->count()}}</th>
          </tr>
          {{-- @foreach ($timeframes_rows as $timeframes_row)
          <tr>
            <td>
              {{$timeframes_row->day}}
            </td>
            @foreach (FSR\Timeframe::where('status', 'active')->orderby('hours_from', 'ASC')->where('day', $timeframes_row->day)->get() as $timeframe)
              <td>
                <input id="chk_availability_{{$timeframe->id}}" name="chk_availability_{{$timeframe->id}}" type="checkbox" />
              </td>
            @endforeach
          </tr>
          @endforeach --}}

      </table>
    </div>
  </div>
</div>



<!-- Modals here (if needed) -->


</section>
<!-- /.content -->

@endsection

@extends('layouts.admin_master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header registration-report-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Извештај за регистрации</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/admin/home"> Admin</a></li>
      <li><a href="#"><i class="fa fa-cutlery"></i> Извештаи</a></li>
      <li><a href="{{route('admin.registration_report')}}"><i class="fa fa-cutlery"></i> Регистрации</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content registration-report-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


  <!-- Filter -->
  <section class="filter registration-report-filter">
  	<div class="filter-wrapper row">
    	<form id="registration-report-filter-form" class="registration-report-filter-form" action="{{route('admin.registration_report')}}" method="post">
    		<input type="hidden" name="post-type" value="filter" />
    		{{csrf_field()}}
    		<div class="filter-container">
    			{{-- <div class="filter-label registration-report-filter-label col-md-4">
    				<label for="registration-report-filter-select">Организација:</label>
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

          @if ($errors->has('registration-report-filter-select'))
            <span class="help-block">
              <strong>{{ $errors->first('registration-report-filter-select') }}</strong>
            </span>
          @endif
    		</div>

      </form>
    </div>
  </section>

<!-- RegistrationByUser table - donors -->
<div id="availability_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Регистрации по корисник - Донатори</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th>Организација</th>
          <th>Корисник</th>
          <th>Статус</th>
          <th>Се регистрирал на</th>
          <th>Бил прифатен/одбиен на</th>
          <th>Време за прифаќање</th>
          <th>Го потврдил меилот на</th>
          <th>Време на потврда на меил</th>
        </tr>

        <!-- Donors -->
        <?php $current_organization_id = 0; ?>
        @foreach ($donors as $donor)
          <tr>

            <?php
              $date_to_date = Carbon::parse($date_to)->addDays(1);
              $donors_count = $donor->organization->donors->where('created_at', '>=', $date_from)
                                                          ->where('created_at', '<=', $date_to_date)
                                                          ->count();

              //donor status
              switch ($donor->status) {
                case 'active':
                  $donor_status = 'активен';
                  break;
                case 'pending':
                  $donor_status = 'чека на одобрување';
                  break;
                case 'rejected':
                  $donor_status = 'одбиен';
                  break;

                default:
                $donor_status = $donor->status;
                break;
              }

              //donor registered at
              $donor_registered_at = Carbon::parse($donor->created_at);

              // Donor approve/reject timestamp
              $donor_approved_log = FSR\Log::where('event', 'admin_approve')
              ->where('user_type','donor')
              ->where('user_id', $donor->id)
              ->first();
              if ($donor_approved_log) {
                $donor_approve_reject_timestamp = Carbon::parse($donor_approved_log->created_at);
              } else {
                $donor_denied_log = FSR\Log::where('event', 'admin_deny')
                ->where('user_type','donor')
                ->where('user_id', $donor->id)
                ->first();
                if ($donor_denied_log) {
                  $donor_approve_reject_timestamp = Carbon::parse($donor_denied_log->created_at);
                } else {
                  $donor_approve_reject_timestamp = '';
                }
              }

              //donor approval/reject time
              if ($donor_approve_reject_timestamp == '') {
                $donor_approval_reject_time = '';
              } else {
                // $donor_approval_reject_time = $donor_registered_at->diffForHumans($donor_approve_reject_timestamp);
                $donor_approval_reject_time = $donor_approve_reject_timestamp->diffForHumans($donor_registered_at);
              }

              //donor email confirm timestamp
              $donor_email_confirm_log = FSR\Log::where('event', 'confirm_email')
              ->where('user_type','donor')
              ->where('user_id', $donor->id)
              ->first();
              if ($donor_email_confirm_log) {
                $donor_email_confirm_timestamp = Carbon::parse($donor_email_confirm_log->created_at);
              } else {
                $donor_email_confirm_timestamp = '';
              }

              //donor email confirm time
              if ($donor_email_confirm_timestamp == '') {
                $donor_email_confirm_time = '';
              } else {
                // $donor_email_confirm_time = $donor_registered_at->diffForHumans($donor_email_confirm_timestamp);
                $donor_email_confirm_time = $donor_email_confirm_timestamp->diffForHumans($donor_registered_at);
              }
            ?>
            @if ($current_organization_id != $donor->organization_id)
              <?php
                $current_organization_id = $donor->organization_id;
              ?>
              <td {{($donors_count > 1) ? 'rowspan=' . $donors_count : ''}}>{{$donor->organization->name}}</td>
            @endif
            <td>{{$donor->first_name . ' ' . $donor->last_name}}</td>

            {{-- <td>{{$donor_status}}</td> --}}
            <td>{{$donor_status}}</td>
            <td>{{$donor_registered_at->format('d.m.Y H:i')}}</td>
            <td>{{($donor_approve_reject_timestamp) ? $donor_approve_reject_timestamp->format('d.m.Y H:i') : ''}}</td>
            <td>{{$donor_approval_reject_time}}</td>
            <td>{{($donor_email_confirm_timestamp) ? $donor_email_confirm_timestamp->format('d.m.Y H:i') : ''}}</td>
            <td>{{$donor_email_confirm_time}}</td>
          </tr>
        @endforeach
        @foreach ($donor_organizations as $organization)
          @if ($organization->donors->where('status','active')->count() == 0)
            <tr>
              <td>{{$organization->name}}</td>
              <td colspan="7">Организацијата нема корисници</td>
            </tr>
          @endif
        @endforeach

      </table>
    </div>
  </div>
</div>

<!-- RegistrationByUser table - csos -->
<div id="availability_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Регистрации по корисник - Приматели</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th>Организација</th>
          <th>Корисник</th>
          <th>Статус</th>
          <th>Се регистрирал на</th>
          <th>Бил прифатен/одбиен на</th>
          <th>Време за прифаќање</th>
          <th>Го потврдил меилот на</th>
          <th>Време на потврда на меил</th>
        </tr>

        <!-- Csos -->
        <?php $current_organization_id = 0; ?>
        @foreach ($csos as $cso)
          <tr>

            <?php
            $date_to_date = Carbon::parse($date_to)->addDays(1);
              $csos_count = $cso->organization->csos->where('created_at', '>=', $date_from)
                                                          ->where('created_at', '<=', $date_to_date)
                                                          ->count();

              //cso status
              switch ($cso->status) {
                case 'active':
                  $cso_status = 'активен';
                  break;
                case 'pending':
                  $cso_status = 'чека на одобрување';
                  break;
                case 'rejected':
                  $cso_status = 'одбиен';
                  break;

                default:
                $cso_status = $cso->status;
                break;
              }

              //cso registered at
              $cso_registered_at = Carbon::parse($cso->created_at);

              // cso approve/reject timestamp
              $cso_approved_log = FSR\Log::where('event', 'admin_approve')
              ->where('user_type','cso')
              ->where('user_id', $cso->id)
              ->first();
              if ($cso_approved_log) {
                $cso_approve_reject_timestamp = Carbon::parse($cso_approved_log->created_at);
              } else {
                $cso_denied_log = FSR\Log::where('event', 'admin_deny')
                ->where('user_type','cso')
                ->where('user_id', $cso->id)
                ->first();
                if ($cso_denied_log) {
                  $cso_approve_reject_timestamp = Carbon::parse($cso_denied_log->created_at);
                } else {
                  $cso_approve_reject_timestamp = '';
                }
              }

              //cso approval/reject time
              if ($cso_approve_reject_timestamp == '') {
                $cso_approval_reject_time = '';
              } else {
                // $cso_approval_reject_time = $cso_registered_at->diffForHumans($cso_approve_reject_timestamp);
                $cso_approval_reject_time = $cso_approve_reject_timestamp->diffForHumans($cso_registered_at);
              }

              //cso email confirm timestamp
              $cso_email_confirm_log = FSR\Log::where('event', 'confirm_email')
              ->where('user_type','cso')
              ->where('user_id', $cso->id)
              ->first();
              if ($cso_email_confirm_log) {
                $cso_email_confirm_timestamp = Carbon::parse($cso_email_confirm_log->created_at);
              } else {
                $cso_email_confirm_timestamp = '';
              }

              //cso email confirm time
              if ($cso_email_confirm_timestamp == '') {
                $cso_email_confirm_time = '';
              } else {
                // $cso_email_confirm_time = $cso_registered_at->diffForHumans($cso_email_confirm_timestamp);
                $cso_email_confirm_time = $cso_email_confirm_timestamp->diffForHumans($cso_registered_at);
              }
            ?>
            @if ($current_organization_id != $cso->organization_id)
              <?php
                $current_organization_id = $cso->organization_id;
              ?>
              <td {{($csos_count > 1) ? 'rowspan=' . $csos_count : ''}}>{{$cso->organization->name}}</td>
            @endif
            <td>{{$cso->first_name . ' ' . $cso->last_name}}</td>

            {{-- <td>{{$cso_status}}</td> --}}
            <td>{{$cso_status}}</td>
            <td>{{$cso_registered_at->format('d.m.Y H:i')}}</td>
            <td>{{($cso_approve_reject_timestamp) ? $cso_approve_reject_timestamp->format('d.m.Y H:i') : ''}}</td>
            <td>{{$cso_approval_reject_time}}</td>
            <td>{{($cso_email_confirm_timestamp) ? $cso_email_confirm_timestamp->format('d.m.Y H:i') : ''}}</td>
            <td>{{$cso_email_confirm_time}}</td>
          </tr>
        @endforeach
        @foreach ($cso_organizations as $organization)
          @if ($organization->csos->where('status','active')->count() == 0)
            <tr>
              <td>{{$organization->name}}</td>
              <td colspan="7">Организацијата нема корисници</td>
            </tr>
          @endif
        @endforeach
      </table>
    </div>
  </div>
</div>

<!-- RegistrationByUser table - hubs -->
<div id="availability_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Регистрации по корисник - Хабови</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th>Организација</th>
          <th>Корисник</th>
          <th>Статус</th>
          <th>Се регистрирал на</th>
          <th>Бил прифатен/одбиен на</th>
          <th>Време за прифаќање</th>
          <th>Го потврдил меилот на</th>
          <th>Време на потврда на меил</th>
        </tr>

        <!-- Hubs -->
        <?php $current_organization_id = 0; ?>
        @foreach ($hubs as $hub)
          <tr>

            <?php
            $date_to_date = Carbon::parse($date_to)->addDays(1);
              $hubs_count = $hub->organization->hubs->where('created_at', '>=', $date_from)
                                                          ->where('created_at', '<=', $date_to_date)
                                                          ->count();

              //hub status
              switch ($hub->status) {
                case 'active':
                  $hub_status = 'активен';
                  break;
                case 'pending':
                  $hub_status = 'чека на одобрување';
                  break;
                case 'rejected':
                  $hub_status = 'одбиен';
                  break;

                default:
                $hub_status = $hub->status;
                break;
              }

              //hub registered at
              $hub_registered_at = Carbon::parse($hub->created_at);

              // hub approve/reject timestamp
              $hub_approved_log = FSR\Log::where('event', 'admin_approve')
              ->where('user_type','hub')
              ->where('user_id', $hub->id)
              ->first();
              if ($hub_approved_log) {
                $hub_approve_reject_timestamp = Carbon::parse($hub_approved_log->created_at);
              } else {
                $hub_denied_log = FSR\Log::where('event', 'admin_deny')
                ->where('user_type','hub')
                ->where('user_id', $hub->id)
                ->first();
                if ($hub_denied_log) {
                  $hub_approve_reject_timestamp = Carbon::parse($hub_denied_log->created_at);
                } else {
                  $hub_approve_reject_timestamp = '';
                }
              }

              //hub approval/reject time
              if ($hub_approve_reject_timestamp == '') {
                $hub_approval_reject_time = '';
              } else {
                // $hub_approval_reject_time = $hub_registered_at->diffForHumans($hub_approve_reject_timestamp);
                $hub_approval_reject_time = $hub_approve_reject_timestamp->diffForHumans($hub_registered_at);
              }

              //hub email confirm timestamp
              $hub_email_confirm_log = FSR\Log::where('event', 'confirm_email')
              ->where('user_type','hub')
              ->where('user_id', $hub->id)
              ->first();
              if ($hub_email_confirm_log) {
                $hub_email_confirm_timestamp = Carbon::parse($hub_email_confirm_log->created_at);
              } else {
                $hub_email_confirm_timestamp = '';
              }

              //hub email confirm time
              if ($hub_email_confirm_timestamp == '') {
                $hub_email_confirm_time = '';
              } else {
                // $hub_email_confirm_time = $hub_registered_at->diffForHumans($hub_email_confirm_timestamp);
                $hub_email_confirm_time = $hub_email_confirm_timestamp->diffForHumans($hub_registered_at);
              }
            ?>
            @if ($current_organization_id != $hub->organization_id)
              <?php
                $current_organization_id = $hub->organization_id;
              ?>
              <td {{($hubs_count > 1) ? 'rowspan=' . $hubs_count : ''}}>{{$hub->organization->name}}</td>
            @endif
            <td>{{$hub->first_name . ' ' . $hub->last_name}}</td>

            {{-- <td>{{$hub_status}}</td> --}}
            <td>{{$hub_status}}</td>
            <td>{{$hub_registered_at->format('d.m.Y H:i')}}</td>
            <td>{{($hub_approve_reject_timestamp) ? $hub_approve_reject_timestamp->format('d.m.Y H:i') : ''}}</td>
            <td>{{$hub_approval_reject_time}}</td>
            <td>{{($hub_email_confirm_timestamp) ? $hub_email_confirm_timestamp->format('d.m.Y H:i') : ''}}</td>
            <td>{{$hub_email_confirm_time}}</td>
          </tr>
        @endforeach
        @foreach ($hub_organizations as $organization)
          @if ($organization->hubs->where('status','active')->count() == 0)
            <tr>
              <td>{{$organization->name}}</td>
              <td colspan="7">Организацијата нема корисници</td>
            </tr>
          @endif
        @endforeach
      </table>
    </div>
  </div>
</div>

<!-- Modals here (if needed) -->


</section>
<!-- /.content -->

@endsection

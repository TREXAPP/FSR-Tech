@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Одобри корисник
    </h1>
    <ol class="breadcrumb">
      <li><a href="/{{Auth::user()->type()}}/home"><i class="fa fa-dashboard"></i> Одобри корисник</a></li>
    </ol>
  </section>



<!-- Main content -->
<section class="content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


<!-- CSO -->
<div class="col-md-6 admin-approve-wrapper-outside admin-approve-cso-wrapper-outside">
  <div class="admin-approve-wrapper">
    <div class="panel admin-approve-title">
      Приматели
    </div>
  	@foreach ($csos as $cso)
  	<!-- Default box -->
  <div class="section-wrapper admin-approve-cso-section-wrapper col-md-12">
  <div id="admin-approve-cso-{{$cso->id}}" name="admin-approve-cso-{{$cso->id}}"></div>
  	<div class="admin-approve-cso-box box admin-approve-cso-{{$cso->id}} two-col-layout-box collapsed-box">
  		<div class="box-header with-border listing-box-header admin-approve-cso-box-header">
  			<a href="#" class=" btn-box-tool listing-box-anchor admin-approve-cso-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

  				<div id="admin-approve-cso-image-preview-{{$cso->id}}" class="admin-approve-cso-image-preview two-col-layout-image-preview">
  					<img class="img-rounded" alt="{{$cso->first_name}}" src="{{Methods::get_user_image_url($cso)}}" />
  				</div>
  				<div class="header-wrapper">
  					<div id="admin-approve-cso-name-{{$cso->id}}" class="admin-approve-cso-name">
  						<span class="admin-approve-cso-listing-title two-col-layout-listing-title">{{$cso->first_name}} {{$cso->last_name}}</span>
  					</div>
  					<div class="box-tools pull-right">
  							<i class="fa fa-caret-down pull-right"></i>
  					</div>
  				</div>

  			</a>
  		</div>
  		<div class="listing-box-body-wrapper">
  			<div class="box-body">
  				<div id="admin-approve-cso-image-wrapper-{{$cso->id}}" class="admin-approve-cso-image-wrapper two-col-layout-image-wrapper col-md-4">
  						<img class="img-rounded" alt="{{$cso->first_name}}" src="{{Methods::get_user_image_url($cso)}}" />
  				</div>

  				<div id="admin-approve-cso-info-wrapper-{{$cso->id}}" class="admin-approve-cso-info-wrapper two-col-layout-info-wrapper col-md-8">

  					<!-- First Name -->
  					<div id="admin-approve-cso-info-first-name-{{$cso->id}}" class="row admin-approve-cso-info-first-name row">
  						<div id="admin-approve-cso-info-first-name-label-{{$cso->id}}" class="admin-approve-cso-info-label admin-approve-cso-info-first-name-label col-md-6">
  							<span>Име:</span>
  						</div>
  						<div id="admin-approve-cso-info-first-name-value-{{$cso->id}}" class="admin-approve-cso-info-value admin-approve-cso-info-first-name-value col-md-6">
  							<span><strong>{{$cso->first_name}}</strong></span>
  						</div>
  					</div>

  					<!-- Last Name -->
  					<div id="admin-approve-cso-info-last-name-{{$cso->id}}" class="row admin-approve-cso-info-last-name row">
  						<div id="admin-approve-cso-info-last-name-label-{{$cso->id}}" class="admin-approve-cso-info-label admin-approve-cso-info-last-name-label col-md-6">
  							<span>Презиме:</span>
  						</div>
  						<div id="admin-approve-cso-info-last-name-value-{{$cso->id}}" class="admin-approve-cso-info-value admin-approve-cso-info-last-name-value col-md-6">
  							<span><strong>{{$cso->last_name}}</strong></span>
  						</div>
  					</div>

  					<!-- phone -->
  					<div id="admin-approve-cso-info-phone-{{$cso->id}}" class="row admin-approve-cso-info-phone row">
  						<div id="admin-approve-cso-info-phone-label-{{$cso->id}}" class="admin-approve-cso-info-label admin-approve-cso-info-phone-label col-md-6">
  							<span>Телефон:</span>
  						</div>
  						<div id="admin-approve-cso-info-phone-value-{{$cso->id}}" class="admin-approve-cso-info-value admin-approve-cso-info-phone-value col-md-6">
  							<span><strong>{{$cso->phone}}</strong></span>
  						</div>
  					</div>

  					<!-- address -->
  					<div id="admin-approve-cso-info-address-{{$cso->id}}" class="row admin-approve-cso-info-address row">
  						<div id="admin-approve-cso-info-address-label-{{$cso->id}}" class="admin-approve-cso-info-label admin-approve-cso-info-address-label col-md-6">
  							<span>Адреса:</span>
  						</div>
  						<div id="admin-approve-cso-info-address-value-{{$cso->id}}" class="admin-approve-cso-info-value admin-approve-cso-info-address-value col-md-6">
  							<span><strong>{{$cso->address}}</strong></span>
  						</div>
  					</div>

  					<!-- organization -->
  					<div id="admin-approve-cso-info-organization-{{$cso->id}}" class="row admin-approve-cso-info-organization row">
  						<div id="admin-approve-cso-info-organization-label-{{$cso->id}}" class="admin-approve-cso-info-label admin-approve-cso-info-organization-label col-md-6">
  							<span>Организација:</span>
  						</div>
  						<div id="admin-approve-cso-info-organization-value-{{$cso->id}}" class="admin-approve-cso-info-value admin-approve-cso-info-organization-value col-md-6">
  							<span><strong>{{$cso->organization->name}}</strong></span>
  						</div>
  					</div>

  					<!-- location -->
  					<div id="admin-approve-cso-info-location-{{$cso->id}}" class="row admin-approve-cso-info-location row">
  						<div id="admin-approve-cso-info-location-label-{{$cso->id}}" class="admin-approve-cso-info-label admin-approve-cso-info-location-label col-md-6">
  							<span>Локација:</span>
  						</div>
  						<div id="admin-approve-cso-info-location-value-{{$cso->id}}" class="admin-approve-cso-info-value admin-approve-cso-info-location-value col-md-6">
  							<span><strong>{{$cso->location->name}}</strong></span>
  						</div>
  					</div>


  				</div>

  			</div>

  			<div class="box-footer">
  					<div class="pull-right">
  						{{-- <a href="{{url('cso/volunteers/' . $volunteer->id)}}" id="edit-volunteer-button-{{$volunteer->id}}" name="edit-volunteer-button-{{$volunteer->id}}"
  							class="btn btn-success edit-volunteer-button">Измени ги податоците</a> --}}
  							<button id="approve-cso-button-{{ $cso->id }}" type="button" data-toggle="modal" data-target="#approve-cso-popup"
  								name="approve-cso-button" class="btn btn-primary approve-cso-button" >Одобри</button>
  							<button id="reject-cso-button-{{ $cso->id }}" type="button" data-toggle="modal" data-target="#reject-cso-popup"
  								name="reject-cso-button" class="btn btn-danger reject-cso-button" >Одбиј</button>
						</div>
  			</div>

  		</div>

  		<!-- /.box-footer-->
  	</div>
  	<!-- /.box -->
  </div>
  @endforeach
  </div>
</div>



<!-- DONOR -->
<div class="col-md-6 admin-approve-wrapper-outside admin-approve-donor-wrapper-outside">
  <div class="admin-approve-wrapper">
    <div class="panel admin-approve-title">
      Донори
    </div>
  	@foreach ($donors as $donor)
  	<!-- Default box -->
  <div class="section-wrapper admin-approve-donor-section-wrapper col-md-12">
  <div id="admin-approve-donor-{{$donor->id}}" name="admin-approve-donor-{{$donor->id}}"></div>
  	<div class="admin-approve-donor-box box admin-approve-donor-{{$donor->id}} two-col-layout-box collapsed-box">
  		<div class="box-header with-border listing-box-header admin-approve-donor-box-header">
  			<a href="#" class=" btn-box-tool listing-box-anchor admin-approve-donor-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

  				<div id="admin-approve-donor-image-preview-{{$donor->id}}" class="admin-approve-donor-image-preview two-col-layout-image-preview">
  						<img class="img-rounded" alt="{{$donor->first_name}}" src="{{Methods::get_user_image_url($donor)}}" />
  				</div>
  				<div class="header-wrapper">
  					<div id="admin-approve-donor-name-{{$donor->id}}" class="admin-approve-donor-name">
  						<span class="admin-approve-donor-listing-title two-col-layout-listing-title">{{$donor->first_name}} {{$donor->last_name}}</span>
  					</div>
  					<div class="box-tools pull-right">
  							<i class="fa fa-caret-down pull-right"></i>
  					</div>
  				</div>

  			</a>
  		</div>
  		<div class="listing-box-body-wrapper">
  			<div class="box-body">
  				<div id="admin-approve-donor-image-wrapper-{{$donor->id}}" class="admin-approve-donor-image-wrapper two-col-layout-image-wrapper col-md-4">
  						<img class="img-rounded" alt="{{$donor->first_name}}" src="{{Methods::get_user_image_url($donor)}}" />
  				</div>

  				<div id="admin-approve-donor-info-wrapper-{{$donor->id}}" class="admin-approve-donor-info-wrapper two-col-layout-info-wrapper col-md-8">

  					<!-- First Name -->
  					<div id="admin-approve-donor-info-first-name-{{$donor->id}}" class="row admin-approve-donor-info-first-name row">
  						<div id="admin-approve-donor-info-first-name-label-{{$donor->id}}" class="admin-approve-donor-info-label admin-approve-donor-info-first-name-label col-md-6">
  							<span>Име:</span>
  						</div>
  						<div id="admin-approve-donor-info-first-name-value-{{$donor->id}}" class="admin-approve-donor-info-value admin-approve-donor-info-first-name-value col-md-6">
  							<span><strong>{{$donor->first_name}}</strong></span>
  						</div>
  					</div>

  					<!-- Last Name -->
  					<div id="admin-approve-donor-info-last-name-{{$donor->id}}" class="row admin-approve-donor-info-last-name row">
  						<div id="admin-approve-donor-info-last-name-label-{{$donor->id}}" class="admin-approve-donor-info-label admin-approve-donor-info-last-name-label col-md-6">
  							<span>Презиме:</span>
  						</div>
  						<div id="admin-approve-donor-info-last-name-value-{{$donor->id}}" class="admin-approve-donor-info-value admin-approve-donor-info-last-name-value col-md-6">
  							<span><strong>{{$donor->last_name}}</strong></span>
  						</div>
  					</div>

  					<!-- phone -->
  					<div id="admin-approve-donor-info-phone-{{$donor->id}}" class="row admin-approve-donor-info-phone row">
  						<div id="admin-approve-donor-info-phone-label-{{$donor->id}}" class="admin-approve-donor-info-label admin-approve-donor-info-phone-label col-md-6">
  							<span>Телефон:</span>
  						</div>
  						<div id="admin-approve-donor-info-phone-value-{{$donor->id}}" class="admin-approve-donor-info-value admin-approve-donor-info-phone-value col-md-6">
  							<span><strong>{{$donor->phone}}</strong></span>
  						</div>
  					</div>

  					<!-- address -->
  					<div id="admin-approve-donor-info-address-{{$donor->id}}" class="row admin-approve-donor-info-address row">
  						<div id="admin-approve-donor-info-address-label-{{$donor->id}}" class="admin-approve-donor-info-label admin-approve-donor-info-address-label col-md-6">
  							<span>Адреса:</span>
  						</div>
  						<div id="admin-approve-donor-info-address-value-{{$donor->id}}" class="admin-approve-donor-info-value admin-approve-donor-info-address-value col-md-6">
  							<span><strong>{{$donor->address}}</strong></span>
  						</div>
  					</div>

  					<!-- organization -->
  					<div id="admin-approve-donor-info-organization-{{$donor->id}}" class="row admin-approve-donor-info-organization row">
  						<div id="admin-approve-donor-info-organization-label-{{$donor->id}}" class="admin-approve-donor-info-label admin-approve-donor-info-organization-label col-md-6">
  							<span>Организација:</span>
  						</div>
  						<div id="admin-approve-donor-info-organization-value-{{$donor->id}}" class="admin-approve-donor-info-value admin-approve-donor-info-organization-value col-md-6">
  							<span><strong>{{$donor->organization->name}}</strong></span>
  						</div>
  					</div>

  					<!-- location -->
  					<div id="admin-approve-donor-info-location-{{$donor->id}}" class="row admin-approve-donor-info-location row">
  						<div id="admin-approve-donor-info-location-label-{{$donor->id}}" class="admin-approve-donor-info-label admin-approve-donor-info-location-label col-md-6">
  							<span>Локација:</span>
  						</div>
  						<div id="admin-approve-donor-info-location-value-{{$donor->id}}" class="admin-approve-donor-info-value admin-approve-donor-info-location-value col-md-6">
  							<span><strong>{{$donor->location->name}}</strong></span>
  						</div>
  					</div>


  				</div>

  			</div>

  			<div class="box-footer">
  					<div class="pull-right">
  						{{-- <a href="{{url('donor/volunteers/' . $volunteer->id)}}" id="edit-volunteer-button-{{$volunteer->id}}" name="edit-volunteer-button-{{$volunteer->id}}"
  							class="btn btn-success edit-volunteer-button">Измени ги податоците</a> --}}
  							<button id="approve-donor-button-{{ $donor->id }}" type="button" data-toggle="modal" data-target="#approve-donor-popup"
  								name="approve-donor-button" class="btn btn-primary approve-donor-button" >Одобри</button>
  							<button id="reject-donor-button-{{ $donor->id }}" type="button" data-toggle="modal" data-target="#reject-donor-popup"
  								name="reject-donor-button" class="btn btn-danger reject-donor-button" >Одбиј</button>
						</div>
  			</div>

  		</div>

  		<!-- /.box-footer-->
  	</div>
  	<!-- /.box -->
  </div>
  @endforeach
  </div>
</div>


<!-- Approve Cso Modal  -->
<div id="approve-cso-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="approve-cso-form" class="approve-cso-form" action="{{ route('admin.approve_users') }}" method="post">
        <input id="approve-cso-id" type="hidden" name="cso_id" value="">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Одобри го примателот</h4>
				</div>
				<div id="approve-cso-body" class="modal-body approve-cso-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го одобрите примателот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="approve-cso" class="btn btn-primary" value="Одобри" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Reject Cso Modal  -->
<div id="reject-cso-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="reject-cso-form" class="reject-cso-form" action="{{ route('admin.approve_users') }}" method="post">
        <input id="reject-cso-id" type="hidden" name="cso_id" value="">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Одбиј го примателот</h4>
				</div>
				<div id="reject-cso-body" class="modal-body reject-cso-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го oдбиете примателот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="reject-cso" class="btn btn-danger" value="Одбиј" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Approve Donor Modal  -->
<div id="approve-donor-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="approve-donor-form" class="approve-donor-form" action="{{ route('admin.approve_users') }}" method="post">
        <input id="approve-donor-id" type="hidden" name="donor_id" value="">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Одобри го донорот</h4>
				</div>
				<div id="approve-donor-body" class="modal-body approve-donor-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го одобрите донорот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="approve-donor" class="btn btn-primary" value="Одобри" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Reject Donor Modal  -->
<div id="reject-donor-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="reject-donor-form" class="reject-donor-form" action="{{ route('admin.approve_users') }}" method="post">
        <input id="reject-donor-id" type="hidden" name="donor_id" value="">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Одбиј го донорот</h4>
				</div>
				<div id="reject-donor-body" class="modal-body reject-donor-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го одбиете донорот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="reject-donor" class="btn btn-primary" value="Одбиј" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>



</section>
<!-- /.content -->

@endsection

@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header hub-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Хабови</span>
		@if ($hubs->count() > 0)
		<span> ({{$hubs->count()}})</span>
		@endif
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="{{route('admin.home')}}"> Админ</a>
		</li>
		<li>
			<a href="{{route('admin.hub_users')}}">
				<i class="fa fa-universal-access"></i> Хабови</a>
		</li>
	</ol>
</section>

<!-- Filter -->
<section class="filter hubs-filter">
	<div class="filter-wrapper row">
	<form id="hubs-filter-form" class="hubs-filter-form" action="{{route('admin.hub_users')}}" method="post">
		<input type="hidden" name="post-type" value="filter" />
		{{csrf_field()}}
		<div class="filter-container col-md-6">
			<div class="filter-label hubs-filter-label col-md-4">
				<label for="hubs-filter-select">Организација:</label>
			</div>
			<div class="filter-select hubs-filter-select col-md-8">
				<select onchange="this.form.submit()" id="organizations_filter_select" class="form-control hubs-filter-select" name="organizations-filter-select" required>
					<option value="">-- Сите --</option>
					@foreach ($organizations as $organization)
						<option value="{{$organization->id}}" {{ ($filters['organization'] == $organization->id) ? ' selected' : '' }}>{{$organization->name}}</option>
					@endforeach
				</select>
				@if ($errors->has('organizations-filter-select'))
					<span class="help-block">
						<strong>{{ $errors->first('organizations-filter-select') }}</strong>
					</span>
				@endif
			</div>
		</div>

</form>
</div>
</section>


<!-- Main content -->
<section class="content hub-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($hubs as $hub)



	<!-- Default box -->
<div class="section-wrapper hubs-section-wrapper col-md-6">
<div id="hubbox{{$hub->id}}" name="hubbox{{$hub->id}}"></div>
	<div class="admin-hub-box box hub-box two-col-layout-box hub-box-{{$hub->id}} {{($hub->id == old('hub_id')) ? 'box-error' : 'collapsed-box' }} {{($hub->is_user) ? ' hub-is-user' : ''}}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="hub-image-preview-{{$hub->id}}" class="hub-image-preview two-col-layout-image-preview">
					<img class="img-rounded" alt="{{$hub->first_name}}" src="{{Methods::get_user_image_url($hub)}}" />
				</div>
				<div class="header-wrapper">
					<div id="hub-name-{{$hub->id}}" class="hub-name">
						<span class="hub-listing-title two-col-layout-listing-title">{{$hub->first_name}} {{$hub->last_name}}</span>
					</div>
					<div id="hub-organization-{{$hub->id}}" class="hub-organization">
						<span class="hub-listing-subtitle two-col-layout-listing-subtitle">{{$hub->organization->name}}</span>
					</div>
					<div class="box-tools pull-right">
						<span class="add-more">Повеќе...</span>
							{{-- <i class="fa fa-caret-down pull-right"></i> --}}
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="hub-image-wrapper-{{$hub->id}}" class="hub-image-wrapper two-col-layout-image-wrapper col-md-4">
							<img class="img-rounded" alt="{{$hub->first_name}}" src="{{Methods::get_user_image_url($hub)}}" />
				</div>

				<div id="hub-info-wrapper-{{$hub->id}}" class="hub-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- First Name -->
					<div id="hub-info-first-name-{{$hub->id}}" class="row hub-info-first-name row">
						<div id="hub-info-first-name-label-{{$hub->id}}" class="hub-info-label hub-info-first-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="hub-info-first-name-value-{{$hub->id}}" class="hub-info-value hub-info-first-name-value col-md-8">
							<span><strong>{{$hub->first_name}}</strong></span>
						</div>
					</div>


				<!-- Last Name -->
					<div id="hub-info-last-name-{{$hub->id}}" class="row hub-info-last-name row">
						<div id="hub-info-last-name-label-{{$hub->id}}" class="hub-info-label hub-info-last-name-label col-md-4">
							<span>Презиме:</span>
						</div>
						<div id="hub-info-last-name-value-{{$hub->id}}" class="hub-info-value hub-info-last-name-value col-md-8">
							<span><strong>{{$hub->last_name}}</strong></span>
						</div>
					</div>

				<!-- Email -->
					<div id="hub-info-email-{{$hub->id}}" class="row hub-info-email row">
						<div id="hub-info-email-label-{{$hub->id}}" class="hub-info-label hub-info-email-label col-md-4">
							<span>Емаил:</span>
						</div>
						<div id="hub-info-email-value-{{$hub->id}}" class="hub-info-value hub-info-email-value col-md-8">
							<span><strong>{{$hub->email}}</strong></span>
						</div>
					</div>

				<!-- Phone -->
					<div id="hub-info-phone-{{$hub->id}}" class="row hub-info-phone row">
						<div id="hub-info-phone-label-{{$hub->id}}" class="hub-info-label hub-info-phone-label col-md-4">
							<span>Телефон:</span>
						</div>
						<div id="hub-info-phone-value-{{$hub->id}}" class="hub-info-value hub-info-phone-value col-md-8">
							<span><strong>{{$hub->phone}}</strong></span>
						</div>
					</div>

				<!-- Address -->
					<div id="hub-info-address-{{$hub->id}}" class="row hub-info-address row">
						<div id="hub-info-address-label-{{$hub->id}}" class="hub-info-label hub-info-address-label col-md-4">
							<span>Адреса:</span>
						</div>
						<div id="hub-info-address-value-{{$hub->id}}" class="hub-info-value hub-info-address-value col-md-8">
							<span><strong>{{$hub->address}}</strong></span>
						</div>
					</div>

				<!-- Organization -->
					<div id="hub-info-organization-{{$hub->id}}" class="row hub-info-organization row">
						<div id="hub-info-organization-label-{{$hub->id}}" class="hub-info-label hub-info-organization-label col-md-4">
							<span>Организација:</span>
						</div>
						<div id="hub-info-organization-value-{{$hub->id}}" class="hub-info-value hub-info-organization-value col-md-8">
							<span><strong>{{$hub->organization->name}}</strong></span>
						</div>
					</div>

				<!-- Region -->
					<div id="hub-info-region-{{$hub->id}}" class="row hub-info-region row">
						<div id="hub-info-region-label-{{$hub->id}}" class="hub-info-label hub-info-region-label col-md-4">
							<span>Регион:</span>
						</div>
						<div id="hub-info-region-value-{{$hub->id}}" class="hub-info-value hub-info-region-value col-md-8">
							<span><strong>{{$hub->region->name}}</strong></span>
						</div>
					</div>
				</div>
			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_hub_user', $hub->id)}}" id="edit-hub-button-{{$hub->id}}" name="edit-hub-button-{{$hub->id}}"
							class="btn btn-success edit-hub-button">Измени ги податоците</a>
							<button id="delete-hub-button-{{ $hub->id }}" type="submit" data-toggle="modal" data-target="#delete-hub-popup"
								name="delete-hub-button" class="btn btn-danger delete-hub-button">Избриши го хабот</button>
							</div>
			</div>
		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-hub-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-hub-form" class="delete-hub-form" action="{{ route('admin.hub_users') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го хабот</h4>
				</div>
				<div id="delete-hub-body" class="modal-body delete-hub-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите хабот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-hub-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

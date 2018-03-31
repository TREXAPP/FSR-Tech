@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header donor-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Донори</span>
		@if ($donors->count() > 0)
		<span> ({{$donors->count()}})</span>
		@endif
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="{{route('admin.home')}}"> Админ</a>
		</li>
		<li>
			<a href="{{route('admin.donor_users')}}">
				<i class="fa fa-universal-access"></i> Донори</a>
		</li>
	</ol>
</section>

<!-- Filter -->
<section class="filter donors-filter">
	<div class="filter-wrapper row">
	<form id="donors-filter-form" class="donors-filter-form" action="{{route('admin.donor_users')}}" method="post">
		<input type="hidden" name="post-type" value="filter" />
		{{csrf_field()}}
		<div class="filter-container col-md-6">
			<div class="filter-label donors-filter-label col-md-4">
				<label for="donors-filter-select">Организација:</label>
			</div>
			<div class="filter-select donors-filter-select col-md-8">
				<select onchange="this.form.submit()" id="organizations_filter_select" class="form-control donors-filter-select" name="organizations-filter-select" required>
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
<section class="content donor-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($donors as $donor)



	<!-- Default box -->
<div class="section-wrapper donors-section-wrapper col-md-6">
<div id="donorbox{{$donor->id}}" name="donorbox{{$donor->id}}"></div>
	<div class="admin-donor-box box donor-box two-col-layout-box donor-box-{{$donor->id}} {{($donor->id == old('donor_id')) ? 'box-error' : 'collapsed-box' }} {{($donor->is_user) ? ' donor-is-user' : ''}}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="donor-image-preview-{{$donor->id}}" class="donor-image-preview two-col-layout-image-preview">
					@if ($donor->profile_image_id)
						<img class="img-rounded" alt="{{$donor->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($donor->profile_image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$donor->first_name}}" src="{{url('img/avatar5.png')}}" />
					@endif
				</div>
				<div class="header-wrapper">
					<div id="donor-name-{{$donor->id}}" class="donor-name">
						<span class="donor-listing-title two-col-layout-listing-title">{{$donor->first_name}} {{$donor->last_name}}</span>
					</div>
					<div id="donor-organization-{{$donor->id}}" class="donor-organization">
						<span class="donor-listing-subtitle two-col-layout-listing-subtitle">{{$donor->organization->name}}</span>
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
				<div id="donor-image-wrapper-{{$donor->id}}" class="donor-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($donor->profile_image_id)
										<img class="img-rounded" alt="{{$donor->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($donor->profile_image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$donor->first_name}}" src="{{url('img/avatar5.png')}}" />
									@endif

				</div>

				<div id="donor-info-wrapper-{{$donor->id}}" class="donor-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- First Name -->
					<div id="donor-info-first-name-{{$donor->id}}" class="row donor-info-first-name row">
						<div id="donor-info-first-name-label-{{$donor->id}}" class="donor-info-label donor-info-first-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="donor-info-first-name-value-{{$donor->id}}" class="donor-info-value donor-info-first-name-value col-md-8">
							<span><strong>{{$donor->first_name}}</strong></span>
						</div>
					</div>


				<!-- Last Name -->
					<div id="donor-info-last-name-{{$donor->id}}" class="row donor-info-last-name row">
						<div id="donor-info-last-name-label-{{$donor->id}}" class="donor-info-label donor-info-last-name-label col-md-4">
							<span>Презиме:</span>
						</div>
						<div id="donor-info-last-name-value-{{$donor->id}}" class="donor-info-value donor-info-last-name-value col-md-8">
							<span><strong>{{$donor->last_name}}</strong></span>
						</div>
					</div>

				<!-- Email -->
					<div id="donor-info-email-{{$donor->id}}" class="row donor-info-email row">
						<div id="donor-info-email-label-{{$donor->id}}" class="donor-info-label donor-info-email-label col-md-4">
							<span>Емаил:</span>
						</div>
						<div id="donor-info-email-value-{{$donor->id}}" class="donor-info-value donor-info-email-value col-md-8">
							<span><strong>{{$donor->email}}</strong></span>
						</div>
					</div>

				<!-- Phone -->
					<div id="donor-info-phone-{{$donor->id}}" class="row donor-info-phone row">
						<div id="donor-info-phone-label-{{$donor->id}}" class="donor-info-label donor-info-phone-label col-md-4">
							<span>Телефон:</span>
						</div>
						<div id="donor-info-phone-value-{{$donor->id}}" class="donor-info-value donor-info-phone-value col-md-8">
							<span><strong>{{$donor->phone}}</strong></span>
						</div>
					</div>

				<!-- Address -->
					<div id="donor-info-address-{{$donor->id}}" class="row donor-info-address row">
						<div id="donor-info-address-label-{{$donor->id}}" class="donor-info-label donor-info-address-label col-md-4">
							<span>Адреса:</span>
						</div>
						<div id="donor-info-address-value-{{$donor->id}}" class="donor-info-value donor-info-address-value col-md-8">
							<span><strong>{{$donor->address}}</strong></span>
						</div>
					</div>

				<!-- Organization -->
					<div id="donor-info-organization-{{$donor->id}}" class="row donor-info-organization row">
						<div id="donor-info-organization-label-{{$donor->id}}" class="donor-info-label donor-info-organization-label col-md-4">
							<span>Организација:</span>
						</div>
						<div id="donor-info-organization-value-{{$donor->id}}" class="donor-info-value donor-info-organization-value col-md-8">
							<span><strong>{{$donor->organization->name}}</strong></span>
						</div>
					</div>

				<!-- Location -->
					<div id="donor-info-location-{{$donor->id}}" class="row donor-info-location row">
						<div id="donor-info-location-label-{{$donor->id}}" class="donor-info-label donor-info-location-label col-md-4">
							<span>Локација:</span>
						</div>
						<div id="donor-info-location-value-{{$donor->id}}" class="donor-info-value donor-info-location-value col-md-8">
							<span><strong>{{$donor->location->name}}</strong></span>
						</div>
					</div>

				</div>

			</div>
			@if (!$donor->is_user)

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_donor_user', $donor->id)}}" id="edit-donor-button-{{$donor->id}}" name="edit-donor-button-{{$donor->id}}"
							class="btn btn-success edit-donor-button">Измени ги податоците</a>
							<button id="delete-donor-button-{{ $donor->id }}" type="submit" data-toggle="modal" data-target="#delete-donor-popup"
								name="delete-donor-button" class="btn btn-danger delete-donor-button">Избриши го донорот</button>
							</div>
			</div>
			@endif
		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-donor-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-donor-form" class="delete-donor-form" action="{{ route('admin.donor_users') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го донорот</h4>
				</div>
				<div id="delete-donor-body" class="modal-body delete-donor-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите донорот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-donor-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

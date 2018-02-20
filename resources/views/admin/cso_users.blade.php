@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header cso-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Приматели</span>
		@if ($csos->count() > 0)
		<span> ({{$csos->count()}})</span>
		@endif
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="{{route('admin.home')}}"> Админ</a>
		</li>
		<li>
			<a href="{{route('admin.cso_users')}}">
				<i class="fa fa-universal-access"></i> Приматели</a>
		</li>
	</ol>
</section>

<!-- Filter -->
<section class="filter csos-filter">
	<div class="filter-wrapper row">
	<form id="csos-filter-form" class="csos-filter-form" action="{{route('admin.cso_users')}}" method="post">
		<input type="hidden" name="post-type" value="filter" />
		{{csrf_field()}}
		<div class="filter-container col-md-6">
			<div class="filter-label csos-filter-label col-md-4">
				<label for="csos-filter-select">Организација:</label>
			</div>
			<div class="filter-select csos-filter-select col-md-8">
				<select onchange="this.form.submit()" id="organizations_filter_select" class="form-control csos-filter-select" name="organizations-filter-select" required>
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
<section class="content cso-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($csos as $cso)



	<!-- Default box -->
<div class="section-wrapper csos-section-wrapper col-md-6">
<div id="csobox{{$cso->id}}" name="csobox{{$cso->id}}"></div>
	<div class="admin-cso-box box cso-box two-col-layout-box cso-box-{{$cso->id}} {{($cso->id == old('cso_id')) ? 'box-error' : 'collapsed-box' }} {{($cso->is_user) ? ' cso-is-user' : ''}}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="cso-image-preview-{{$cso->id}}" class="cso-image-preview two-col-layout-image-preview">
					@if ($cso->image_id)
						<img class="img-rounded" alt="{{$cso->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($cso->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$cso->first_name}}" src="{{url('img/avatar5.png')}}" />
					@endif
				</div>
				<div class="header-wrapper">
					<div id="cso-name-{{$cso->id}}" class="cso-name">
						<span class="cso-listing-title two-col-layout-listing-title">{{$cso->first_name}} {{$cso->last_name}}</span>
					</div>
					<div id="cso-organization-{{$cso->id}}" class="cso-organization">
						<span class="cso-listing-subtitle two-col-layout-listing-subtitle">{{$cso->organization->name}}</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="cso-image-wrapper-{{$cso->id}}" class="cso-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($cso->image_id)
										<img class="img-rounded" alt="{{$cso->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($cso->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$cso->first_name}}" src="{{url('img/avatar5.png')}}" />
									@endif

				</div>

				<div id="cso-info-wrapper-{{$cso->id}}" class="cso-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- First Name -->
					<div id="cso-info-first-name-{{$cso->id}}" class="row cso-info-first-name row">
						<div id="cso-info-first-name-label-{{$cso->id}}" class="cso-info-label cso-info-first-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="cso-info-first-name-value-{{$cso->id}}" class="cso-info-value cso-info-first-name-value col-md-8">
							<span><strong>{{$cso->first_name}}</strong></span>
						</div>
					</div>


				<!-- Last Name -->
					<div id="cso-info-last-name-{{$cso->id}}" class="row cso-info-last-name row">
						<div id="cso-info-last-name-label-{{$cso->id}}" class="cso-info-label cso-info-last-name-label col-md-4">
							<span>Презиме:</span>
						</div>
						<div id="cso-info-last-name-value-{{$cso->id}}" class="cso-info-value cso-info-last-name-value col-md-8">
							<span><strong>{{$cso->last_name}}</strong></span>
						</div>
					</div>

				<!-- Email -->
					<div id="cso-info-email-{{$cso->id}}" class="row cso-info-email row">
						<div id="cso-info-email-label-{{$cso->id}}" class="cso-info-label cso-info-email-label col-md-4">
							<span>Емаил:</span>
						</div>
						<div id="cso-info-email-value-{{$cso->id}}" class="cso-info-value cso-info-email-value col-md-8">
							<span><strong>{{$cso->email}}</strong></span>
						</div>
					</div>

				<!-- Phone -->
					<div id="cso-info-phone-{{$cso->id}}" class="row cso-info-phone row">
						<div id="cso-info-phone-label-{{$cso->id}}" class="cso-info-label cso-info-phone-label col-md-4">
							<span>Телефон:</span>
						</div>
						<div id="cso-info-phone-value-{{$cso->id}}" class="cso-info-value cso-info-phone-value col-md-8">
							<span><strong>{{$cso->phone}}</strong></span>
						</div>
					</div>

				<!-- Address -->
					<div id="cso-info-address-{{$cso->id}}" class="row cso-info-address row">
						<div id="cso-info-address-label-{{$cso->id}}" class="cso-info-label cso-info-address-label col-md-4">
							<span>Адреса:</span>
						</div>
						<div id="cso-info-address-value-{{$cso->id}}" class="cso-info-value cso-info-address-value col-md-8">
							<span><strong>{{$cso->address}}</strong></span>
						</div>
					</div>

				<!-- Organization -->
					<div id="cso-info-organization-{{$cso->id}}" class="row cso-info-organization row">
						<div id="cso-info-organization-label-{{$cso->id}}" class="cso-info-label cso-info-organization-label col-md-4">
							<span>Организација:</span>
						</div>
						<div id="cso-info-organization-value-{{$cso->id}}" class="cso-info-value cso-info-organization-value col-md-8">
							<span><strong>{{$cso->organization->name}}</strong></span>
						</div>
					</div>

				<!-- Location -->
					<div id="cso-info-location-{{$cso->id}}" class="row cso-info-location row">
						<div id="cso-info-location-label-{{$cso->id}}" class="cso-info-label cso-info-location-label col-md-4">
							<span>Локација:</span>
						</div>
						<div id="cso-info-location-value-{{$cso->id}}" class="cso-info-value cso-info-location-value col-md-8">
							<span><strong>{{$cso->location->name}}</strong></span>
						</div>
					</div>

				</div>

			</div>
			@if (!$cso->is_user)

			<div class="box-footer">
					<div class="pull-right">
						<a href="#" id="edit-food-type-button-{{$cso->id}}" name="edit-food-type-button-{{$cso->id}}"
							class="btn btn-success edit-food-type-button" disabled>Измени ги податоците</a>
							<button id="delete-food-type-button-{{ $cso->id }}" type="submit" data-toggle="modal" data-target="#delete-food-type-popup"
								name="delete-food-type-button" class="btn btn-danger delete-food-type-button" disabled >Избриши го примателот</button>
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
<div id="delete-cso-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-cso-form" class="delete-cso-form" action="{{ route('admin.cso_users') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го волонтерот</h4>
				</div>
				<div id="delete-cso-body" class="modal-body delete-cso-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите волонтерот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="delete-cso-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

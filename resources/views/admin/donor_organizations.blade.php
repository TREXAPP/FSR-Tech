@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Организации - Донори </span>
		@if ($organizations->count() > 0)
		<span> ({{$organizations->count()}})</span>
		@endif
		<a href="{{route('admin.new_organization')}}" id="new-organization-button" name="new-organization-button"
		class="btn btn-success new-organization-button"><i class="fa fa-plus"></i>Додади нова организација</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="{{route('admin.donor_organizations')}}">
				<i class="fa fa-universal-access"></i> Организации-Донори</a>
		</li>
	</ol>
</section>


<!-- Filter -->
<section class="filter organizations-filter">
	<div class="filter-wrapper row">
	<form id="organizations-filter-form" class="organizations-filter-form" action="{{route('admin.donor_organizations')}}" method="post">
		<input type="hidden" name="post-type" value="filter" />
		{{csrf_field()}}
		<div class="filter-container col-md-6">
			<div class="filter-label organizations-filter-label col-md-4">
				<label for="donor-types-filter-select">Тип на донори:</label>
			</div>
			<div class="filter-select organizations-filter-select col-md-8">
				<select onchange="this.form.submit()" id="donor_types_filter_select" class="form-control donor-types-filter-select" name="donor-types-filter-select" required>
					<option value="">-- Сите --</option>
					@foreach ($donor_types as $donor_type)
						<option value="{{$donor_type->id}}" {{ ($filters['donor_type'] == $donor_type->id) ? ' selected' : '' }}>{{$donor_type->name}}</option>
					@endforeach
				</select>
				@if ($errors->has('donor-types-filter-select'))
					<span class="help-block">
						<strong>{{ $errors->first('donor-types-filter-select') }}</strong>
					</span>
				@endif
			</div>
		</div>

</form>
</div>
</section>


<!-- Main content -->
<section class="content organizations-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($organizations as $organization)



	<!-- Default box -->
<div class="section-wrapper organizations-section-wrapper col-md-6">
<div id="organizationbox{{$organization->id}}" name="organizationbox{{$organization->id}}"></div>
	<div class="admin-organization-box box organization-box two-col-layout-box organization-box-{{$organization->id}} {{($organization->id == old('organization_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="organization-image-preview-{{$organization->id}}" class="organization-image-preview two-col-layout-image-preview">
					@if ($organization->image_id)
						<img class="img-rounded" alt="{{$organization->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($organization->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$organization->name}}" src="{{url('img/organizations.png')}}" />
					@endif
				</div>
				<div class="header-wrapper">
					<div id="organization-name-{{$organization->id}}" class="organization-name">
						<span class="organization-listing-title two-col-layout-listing-title">{{$organization->name}}</span>
					</div>
					<div id="organization-users-no-{{$organization->id}}" class="organization-users-no">
						<span class="organization-listing-subtitle two-col-layout-listing-subtitle"><span id="users-no-{{$organization->id}}">{{$organization->donors->where('status','active')->count()}}</span> корисници</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="organization-image-wrapper-{{$organization->id}}" class="organization-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($organization->image_id)
										<img class="img-rounded" alt="{{$organization->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($organization->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$organization->name}}" src="{{url('img/organizations.png')}}" />
									@endif

				</div>

				<div id="organization-info-wrapper-{{$organization->id}}" class="organization-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- Name -->
					<div id="organization-info-name-{{$organization->id}}" class="row organization-info-name row">
						<div id="organization-info-name-label-{{$organization->id}}" class="organization-info-label organization-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="organization-info-name-value-{{$organization->id}}" class="organization-info-value organization-info-name-value col-md-8">
							<span><strong>{{$organization->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="organization-info-description-{{$organization->id}}" class="row organization-info-description row">
						<div id="organization-info-description-label-{{$organization->id}}" class="organization-info-label organization-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="organization-info-description-value-{{$organization->id}}" class="organization-info-value organization-info-description-value col-md-8">
							<span><strong>{{$organization->description}}</strong></span>
						</div>
					</div>

					<!-- Working hours from -->
					<div id="organization-info-working-hours-from-{{$organization->id}}" class="row organization-info--working-hours-from row">
						<div id="organization-info--working-hours-from-label-{{$organization->id}}" class="organization-info-label organization-info--working-hours-from-label col-md-4">
							<span>Работно време од:</span>
						</div>
						<div id="organization-info--working-hours-from-value-{{$organization->id}}" class="organization-info-value organization-info--working-hours-from-value col-md-8">
							<span><strong>{{$organization->working_hours_from}}</strong></span>
						</div>
					</div>

					<!-- Working hours to -->
					<div id="organization-info-working-hours-to-{{$organization->id}}" class="row organization-info--working-hours-to row">
						<div id="organization-info--working-hours-to-label-{{$organization->id}}" class="organization-info-label organization-info--working-hours-to-label col-md-4">
							<span>Работно време до:</span>
						</div>
						<div id="organization-info--working-hours-to-value-{{$organization->id}}" class="organization-info-value organization-info--working-hours-to-value col-md-8">
							<span><strong>{{$organization->working_hours_to}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="#" id="edit-donor-organization-button-{{$organization->id}}" name="edit-donor-organization-button-{{$organization->id}}"
							class="btn btn-success edit-donor-organization-button" disabled>Измени ги податоците</a>
							<button id="delete-donor-organization-button-{{ $organization->id }}" type="submit" data-toggle="modal" data-target="#delete-donor-organization-popup"
								name="delete-donor-organization-button" class="btn btn-danger delete-donor-organization-button">Избриши ја организацијата</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-donor-organization-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-donor-organization-form" class="delete-donor-organization-form" action="{{ route('admin.donor_organizations') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го волонтерот</h4>
				</div>
				<div id="delete-donor-organization-body" class="modal-body delete-donor-organization-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						<div>Дали сте сигурни дека сакате да го избришите организацијата?</div>
						<div>ВНИМАНИЕ: <span id="delete-popup-users-no"></span> корисници, заедно со нивните активни донации ќе бидат исто така избришани!</div>
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-donor-organization-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

	<!-- Add volunteer Modal -->
	<div id="add-volunteer-popup" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<form id="add-volunteer-form" class="add-volunteer-form" action="{{ route('cso.active_listings.add_volunteer') }}" method="post"
				 enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 id="popup-title" class="modal-title popup-title">Нов Волонтер</h4>
					</div>
					<div id="add-volunteer-body" class="modal-body add-volunteer-body">
						<!-- Form content-->
						<h5 id="popup-info" class="popup-info row italic">
							Внесете ги податоците за волонтерот:
						</h5>

						<!-- first name -->
						<div id="first-name-form-group" class="form-group row">
							<label for="first_name" class="col-md-2 col-md-offset-2 control-label">Име:</label>
							<div class="col-md-6">
								<input id="first_name" type="text" class="form-control" name="first_name" {{-- value="" style="text-align: center;" required> --}} value="" style="text-align: center;" >
								<span id="first-name-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- last name -->
						<div id="last-name-form-group" class="form-group row">
							<label for="last_name" class="col-md-2 col-md-offset-2 control-label">Презиме:</label>
							<div class="col-md-6">
								<input id="last_name" type="text" class="form-control" name="last_name" value="" style="text-align: center;"> {{-- value="" style="text-align: center;" required > --}}
								<span id="last-name-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- email -->
						<div id="email-form-group" class="form-group row">
							<label for="email" class="col-md-2 col-md-offset-2 control-label">Емаил:</label>
							<div class="col-md-6">
								<input id="email" type="email" class="form-control" name="email" {{-- value="" style="text-align: center;" required> --}} value="" style="text-align: center;" >
								<span id="email-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- email -->
						<div id="phone-form-group" class="form-group row">
							<label for="phone" class="col-md-2 col-md-offset-2 control-label">Контакт:</label>
							<div class="col-md-6">
								<input id="phone" type="text" class="form-control" name="phone" value="" style="text-align: center;"> {{-- value="" style="text-align: center;" required > --}}
								<span id="phone-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- Upload image -->
						<div id="image-form-group" class="form-group{{ $errors->has('image') ? ' has-error' : '' }} row">
							<label for="image" class="col-md-2 col-md-offset-2 control-label">Слика</label>

							<div class="col-md-6">
								<input id="image" type="file" class="form-control" name="image" value="{{ old('image') }}">
								<span id="image-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						{{--
						<i id="popup-loading" class="fa fa-spinner fa-pulse fa-2x fa-fw"></i> --}}
						<i id="popup-loading" class="popup-loading"></i>
						<input type="submit" id="add-volunteer-popup-submit" name="add-volunteer-popup-submit" class="btn btn-primary" value="Прифати"
						/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
					</div>
				</form>
			</div>
		</div>
	</div>


</section>
<!-- /.content -->

@endsection

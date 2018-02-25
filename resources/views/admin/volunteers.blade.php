@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Волонтери</span>
		@if ($volunteers->count() > 0)
		<span> ({{$volunteers->count()}})</span>
		@endif
		<a href="{{route('admin.new_volunteer')}}" id="new-volunteer-button" name="new-volunteer-button"
		class="btn btn-success new-volunteer-button"><i class="fa fa-plus"></i>Додади нов волонтер</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/volunteers">
				<i class="fa fa-universal-access"></i> Волонтери</a>
		</li>
	</ol>
</section>

<!-- Filter -->
<section class="filter volunteers-filter">
	<div class="filter-wrapper row">
	<form id="volunteers-filter-form" class="volunteers-filter-form" action="{{route('admin.volunteers')}}" method="post">
		<input type="hidden" name="post-type" value="filter" />
		{{csrf_field()}}
		<div class="filter-container col-md-6">
			<div class="filter-label volunteers-filter-label col-md-4">
				<label for="volunteers-filter-select">Организација:</label>
			</div>
			<div class="filter-select volunteers-filter-select col-md-8">
				<select onchange="this.form.submit()" id="organizations_filter_select" class="form-control volunteers-filter-select" name="organizations-filter-select" required>
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
<section class="content volunteer-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($volunteers as $volunteer)



	<!-- Default box -->
<div class="section-wrapper volunteers-section-wrapper col-md-6">
<div id="volunteerbox{{$volunteer->id}}" name="volunteerbox{{$volunteer->id}}"></div>
	<div class="admin-volunteer-box box volunteer-box two-col-layout-box volunteer-box-{{$volunteer->id}} {{($volunteer->id == old('volunteer_id')) ? 'box-error' : 'collapsed-box' }} {{($volunteer->is_user) ? ' volunteer-is-user' : ''}}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="volunteer-image-preview-{{$volunteer->id}}" class="volunteer-image-preview two-col-layout-image-preview">
					<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{Methods::get_volunteer_image_url($volunteer)}}" />
				</div>
				<div class="header-wrapper">
					<div id="volunteer-name-{{$volunteer->id}}" class="volunteer-name">
						<span class="volunteer-listing-title two-col-layout-listing-title">{{$volunteer->first_name}} {{$volunteer->last_name}}</span>
					</div>
					<div id="volunteer-organization-{{$volunteer->id}}" class="volunteer-organization">
						<span class="volunteer-listing-subtitle two-col-layout-listing-subtitle">{{$volunteer->organization->name}}</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="volunteer-image-wrapper-{{$volunteer->id}}" class="volunteer-image-wrapper two-col-layout-image-wrapper col-md-4">
					<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{Methods::get_volunteer_image_url($volunteer)}}" />
				</div>

				<div id="volunteer-info-wrapper-{{$volunteer->id}}" class="volunteer-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- First Name -->
					<div id="volunteer-info-first-name-{{$volunteer->id}}" class="row volunteer-info-first-name row">
						<div id="volunteer-info-first-name-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-first-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="volunteer-info-first-name-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-first-name-value col-md-8">
							<span><strong>{{$volunteer->first_name}}</strong></span>
						</div>
					</div>


				<!-- Last Name -->
					<div id="volunteer-info-last-name-{{$volunteer->id}}" class="row volunteer-info-last-name row">
						<div id="volunteer-info-last-name-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-last-name-label col-md-4">
							<span>Презиме:</span>
						</div>
						<div id="volunteer-info-last-name-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-last-name-value col-md-8">
							<span><strong>{{$volunteer->last_name}}</strong></span>
						</div>
					</div>

				<!-- Email -->
					<div id="volunteer-info-email-{{$volunteer->id}}" class="row volunteer-info-email row">
						<div id="volunteer-info-email-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-email-label col-md-4">
							<span>Емаил:</span>
						</div>
						<div id="volunteer-info-email-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-email-value col-md-8">
							<span><strong>{{$volunteer->email}}</strong></span>
						</div>
					</div>

				<!-- Phone -->
					<div id="volunteer-info-phone-{{$volunteer->id}}" class="row volunteer-info-phone row">
						<div id="volunteer-info-phone-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-phone-label col-md-4">
							<span>Телефон:</span>
						</div>
						<div id="volunteer-info-phone-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-phone-value col-md-8">
							<span><strong>{{$volunteer->phone}}</strong></span>
						</div>
					</div>

				</div>

			</div>
			@if (!$volunteer->is_user)

			<div class="box-footer">
					<div class="pull-right">
						<a href="#" id="edit-food-type-button-{{$volunteer->id}}" name="edit-food-type-button-{{$volunteer->id}}"
							class="btn btn-success edit-food-type-button" disabled>Измени ги податоците</a>
							<button id="delete-food-type-button-{{ $volunteer->id }}" type="submit" data-toggle="modal" data-target="#delete-food-type-popup"
								name="delete-food-type-button" class="btn btn-danger delete-food-type-button" disabled >Избриши го волонтерот</button>
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
<div id="delete-volunteer-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-volunteer-form" class="delete-volunteer-form" action="{{ route('admin.volunteers') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го волонтерот</h4>
				</div>
				<div id="delete-volunteer-body" class="modal-body delete-volunteer-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите волонтерот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="delete-volunteer-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

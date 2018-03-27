@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Организации - Приматели </span>
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
			<a href="{{route('admin.cso_organizations')}}">
				<i class="fa fa-universal-access"></i> Организации-Приматели</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content organizations-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($organizations->get() as $organization)



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
						<span class="organization-listing-subtitle two-col-layout-listing-subtitle"><span id="users-no-{{$organization->id}}">{{$organization->csos->where('status','active')->count()}}</span> корисници, <span id="volunteers-no-{{$organization->id}}">{{$organization->volunteers->where('is_user','0')->where('status','active')->count()}}</span> волонтери</span>
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

					<!-- Address -->
					<div id="organization-info-address-{{$organization->id}}" class="row organization-info-address row">
						<div id="organization-info-address-label-{{$organization->id}}" class="organization-info-label organization-info-address-label col-md-4">
							<span>Адреса:</span>
						</div>
						<div id="organization-info-address-value-{{$organization->id}}" class="organization-info-value organization-info-address-value col-md-8">
							<span><strong>{{$organization->address}}</strong></span>
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

				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_organization', $organization->id)}}" id="edit-cso-organization-button-{{$organization->id}}" name="edit-cso-organization-button-{{$organization->id}}"
							class="btn btn-success edit-cso-organization-button">Измени ги податоците</a>
							<button id="delete-cso-organization-button-{{ $organization->id }}" type="submit" data-toggle="modal" data-target="#delete-cso-organization-popup"
								name="delete-cso-organization-button" class="btn btn-danger delete-cso-organization-button" >Избриши ја организацијата</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-cso-organization-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-cso-organization-form" class="delete-cso-organization-form" action="{{ route('admin.cso_organizations') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши ја организацијата</h4>
				</div>
				<div id="delete-cso-organization-body" class="modal-body delete-cso-organization-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						<div>Дали сте сигурни дека сакате да го избришите организацијата?</div>
						<div>ВНИМАНИЕ: <span id="delete-popup-users-no"></span> корисници и <span id="delete-popup-volunteers-no"></span> волонтери, како и сите нивни прифатени донации ќе бидат исто така избришани!</div>
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-cso-organization-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

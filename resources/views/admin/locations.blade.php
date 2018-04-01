@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header location-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Локации </span>
		@if ($locations->count() > 0)
		<span> ({{$locations->count()}})</span>
		@endif
		<a href="{{route('admin.new_location')}}" id="new-location-button" name="new-location-button"
		class="btn btn-success new-location-button"><i class="fa fa-plus"></i>Додади нова локација</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-universal-access"></i> Локации</a>
		</li>
		<li>
			<a href="{{route('admin.locations')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content locations-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($locations as $location)



	<!-- Default box -->
<div class="section-wrapper locations-section-wrapper col-md-6">
<div id="foodtypebox{{$location->id}}" name="foodtypebox{{$location->id}}"></div>
	<div class="admin-location-box box location-box two-col-layout-box location-box-{{$location->id}} {{($location->id == old('location_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div class="header-wrapper">
					<div id="location-name-{{$location->id}}" class="location-name">
						<span class="location-listing-title two-col-layout-listing-title">{{$location->name}}</span>
						<br>
						<small>{{$location->donors->count()}} донори и {{$location->csos->count()}} приматели</small>
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

				<div id="location-info-wrapper-{{$location->id}}" class="location-info-wrapper two-col-layout-info-wrapper col-md-12">

					<!-- Name -->
					<div id="location-info-name-{{$location->id}}" class="row location-info-name row">
						<div id="location-info-name-label-{{$location->id}}" class="location-info-label location-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="location-info-name-value-{{$location->id}}" class="location-info-value location-info-name-value col-md-8">
							<span><strong>{{$location->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="location-info-description-{{$location->id}}" class="row location-info-description row">
						<div id="location-info-description-label-{{$location->id}}" class="location-info-label location-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="location-info-description-value-{{$location->id}}" class="location-info-value location-info-description-value col-md-8">
							<span><strong>{{$location->description}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_location', $location->id)}}" id="edit-location-button-{{$location->id}}" name="edit-location-button-{{$location->id}}"
							class="btn btn-success edit-location-button">Измени ги податоците</a>
							<button id="delete-location-button-{{ $location->id }}" type="submit" data-toggle="modal" data-target="#delete-location-popup"
								name="delete-location-button" class="btn btn-danger delete-location-button"
								{{($location->csos->count() || $location->donors->count()) ? ' disabled' : '' }}>Избриши ја локацијата</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-location-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-location-form" class="delete-location-form" action="{{ route('admin.locations') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши ја локацијата</h4>
				</div>
				<div id="delete-location-body" class="modal-body delete-location-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да ја избришите локацијата?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-location-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>


</section>
<!-- /.content -->

@endsection

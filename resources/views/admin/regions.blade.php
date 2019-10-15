@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header region-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Региони </span>
		@if ($regions->count() > 0)
		<span> ({{$regions->count()}})</span>
		@endif
		<a href="{{route('admin.new_region')}}" id="new-region-button" name="new-region-button"
		class="btn btn-success new-region-button"><i class="fa fa-plus"></i>Додади нов регион</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-universal-access"></i> Региони</a>
		</li>
		<li>
			<a href="{{route('admin.regions')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content regions-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($regions as $region)



	<!-- Default box -->
<div class="section-wrapper regions-section-wrapper col-md-6">
<div id="foodtypebox{{$region->id}}" name="foodtypebox{{$region->id}}"></div>
	<div class="admin-region-box box region-box two-col-layout-box region-box-{{$region->id}} {{($region->id == old('region_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div class="header-wrapper">
					<div id="region-name-{{$region->id}}" class="region-name">
						<span class="region-listing-title two-col-layout-listing-title">{{$region->name}}</span>
						<br>
						<small>{{$region->locations->count()}} локации</small>
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

				<div id="region-info-wrapper-{{$region->id}}" class="region-info-wrapper two-col-layout-info-wrapper col-md-12">

					<!-- Name -->
					<div id="region-info-name-{{$region->id}}" class="row region-info-name row">
						<div id="region-info-name-label-{{$region->id}}" class="region-info-label region-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="region-info-name-value-{{$region->id}}" class="region-info-value region-info-name-value col-md-8">
							<span><strong>{{$region->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="region-info-description-{{$region->id}}" class="row region-info-description row">
						<div id="region-info-description-label-{{$region->id}}" class="region-info-label region-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="region-info-description-value-{{$region->id}}" class="region-info-value region-info-description-value col-md-8">
							<span><strong>{{$region->description}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_region', $region->id)}}" id="edit-region-button-{{$region->id}}" name="edit-region-button-{{$region->id}}"
							class="btn btn-success edit-region-button">Измени ги податоците</a>
							<button id="delete-region-button-{{ $region->id }}" type="submit" data-toggle="modal" data-target="#delete-region-popup"
								name="delete-region-button" class="btn btn-danger delete-region-button"
								{{($region->locations->count()) ? ' disabled' : '' }}>Избриши го регионот</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-region-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-region-form" class="delete-region-form" action="{{ route('admin.regions') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го регионот</h4>
				</div>
				<div id="delete-region-body" class="modal-body delete-region-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите регионот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-region-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>


</section>
<!-- /.content -->

@endsection

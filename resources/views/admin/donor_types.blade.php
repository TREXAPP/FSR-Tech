@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Типови на донори </span>
		@if ($donor_types->count() > 0)
		<span> ({{$donor_types->count()}})</span>
		@endif
		<a href="{{route('admin.new_donor_type')}}" id="new-donor-type-button" name="new-donor-type-button"
		class="btn btn-success new-donor-type-button"><i class="fa fa-plus"></i>Додади нов тип на донор</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>

		<li>
			<a href="#">
				<i class="fa fa-universal-access"></i> Типови на донори</a>
		</li>
		<li>
			<a href="{{route('admin.donor_types')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content donor-types-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($donor_types as $donor_type)



	<!-- Default box -->
<div class="section-wrapper donor-types-section-wrapper col-md-6">
<div id="donortypebox{{$donor_type->id}}" name="donortypebox{{$donor_type->id}}"></div>
	<div class="admin-donor-type-box box donor-type-box two-col-layout-box donor-type-box-{{$donor_type->id}} {{($donor_type->id == old('donor_type_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">


				<div class="header-wrapper">
					<div id="donor-type-name-{{$donor_type->id}}" class="donor-type-name">
						<span class="donor-type-listing-title two-col-layout-listing-title">{{$donor_type->name}}</span>
						<br>
						<small>{{$donor_type->organizations->count()}} организации</small>
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


				<div id="donor-type-info-wrapper-{{$donor_type->id}}" class="donor-type-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- Name -->
					<div id="donor-type-info-name-{{$donor_type->id}}" class="row donor-type-info-name row">
						<div id="donor-type-info-name-label-{{$donor_type->id}}" class="donor-type-info-label donor-type-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="donor-type-info-name-value-{{$donor_type->id}}" class="donor-type-info-value donor-type-info-name-value col-md-8">
							<span><strong>{{$donor_type->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="donor-type-info-description-{{$donor_type->id}}" class="row donor-type-info-description row">
						<div id="donor-type-info-description-label-{{$donor_type->id}}" class="donor-type-info-label donor-type-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="donor-type-info-description-value-{{$donor_type->id}}" class="donor-type-info-value donor-type-info-description-value col-md-8">
							<span><strong>{{$donor_type->description}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_donor_type', $donor_type->id)}}" id="edit-donor-type-button-{{$donor_type->id}}" name="edit-donor-type-button-{{$donor_type->id}}"
							class="btn btn-success edit-donor-type-button">Измени ги податоците</a>
							<button id="delete-donor-type-button-{{ $donor_type->id }}" type="submit" data-toggle="modal" data-target="#delete-donor-type-popup"
								name="delete-donor-type-button" class="btn btn-danger delete-donor-type-button"
								{{($donor_type->organizations->count()) ? ' disabled' : '' }}>Избриши</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-donor-type-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-donor-type-form" class="delete-donor-type-form" action="{{ route('admin.donor_types') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го типот на донор</h4>
				</div>
				<div id="delete-donor-type-body" class="modal-body delete-donor-type-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите типот на донор?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-donor-type-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>


</section>
<!-- /.content -->

@endsection

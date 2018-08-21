@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Типови на транспорт </span>
		@if ($transport_types->count() > 0)
		<span> ({{$transport_types->count()}})</span>
		@endif
		<a href="{{route('admin.new_transport_type')}}" id="new-transport-type-button" name="new-transport-type-button"
		class="btn btn-success new-transport-type-button"><i class="fa fa-plus"></i>Додади нов тип на транспорт</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-universal-access"></i> Типови на транспорт</a>
		</li>
		<li>
			<a href="{{route('admin.transport_types')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content transport-types-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($transport_types as $transport_type)
<?php
	$volunteers_count = FSR\VolunteersTransportType::where('status','active')->where('transport_type_id', $transport_type->id)->get()->count();
?>


	<!-- Default box -->
<div class="section-wrapper transport-types-section-wrapper col-md-6">
<div id="transporttypebox{{$transport_type->id}}" name="transporttypebox{{$transport_type->id}}"></div>
	<div class="admin-transport-type-box box transport-type-box two-col-layout-box transport-type-box-{{$transport_type->id}} {{($transport_type->id == old('transport_type_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				{{-- <div id="transport-type-image-preview-{{$transport_type->id}}" class="transport-type-image-preview two-col-layout-image-preview">
					@if ($transport_type->image_id)
						<img class="img-rounded" alt="{{$transport_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($transport_type->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$transport_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
					@endif
				</div> --}}
				<div class="header-wrapper">
					<div id="transport-type-name-{{$transport_type->id}}" class="transport-type-name">
						<span class="transport-type-listing-title two-col-layout-listing-title">{{$transport_type->name}}</span>
						<br>
						<small>{{$volunteers_count}} волонтери</small>
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
				{{-- <div id="transport-type-image-wrapper-{{$transport_type->id}}" class="transport-type-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($transport_type->image_id)
										<img class="img-rounded" alt="{{$transport_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($transport_type->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$transport_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
									@endif

				</div> --}}

				<div id="transport-type-info-wrapper-{{$transport_type->id}}" class="transport-type-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- Name -->
					<div id="transport-type-info-name-{{$transport_type->id}}" class="row transport-type-info-name row">
						<div id="transport-type-info-name-label-{{$transport_type->id}}" class="transport-type-info-label transport-type-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="transport-type-info-name-value-{{$transport_type->id}}" class="transport-type-info-value transport-type-info-name-value col-md-8">
							<span><strong>{{$transport_type->name}}</strong></span>
						</div>
					</div>

					<!-- Quantity -->
					<div id="transport-type-info-quantity-{{$transport_type->id}}" class="row transport-type-info-quantity row">
						<div id="transport-type-info-quantity-label-{{$transport_type->id}}" class="transport-type-info-label transport-type-info-quantity-label col-md-4">
							<span>Количина:</span>
						</div>
						<div id="transport-type-info-quantity-value-{{$transport_type->id}}" class="transport-type-info-value transport-type-info-quantity-value col-md-8">
							<span><strong>{{$transport_type->quantity}}</strong></span>
						</div>
					</div>

					<!-- Comment -->
					<div id="transport-type-info-comment-{{$transport_type->id}}" class="row transport-type-info-comment row">
						<div id="transport-type-info-comment-label-{{$transport_type->id}}" class="transport-type-info-label transport-type-info-comment-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="transport-type-info-comment-value-{{$transport_type->id}}" class="transport-type-info-value transport-type-info-comment-value col-md-8">
							<span><strong>{{$transport_type->comment}}</strong></span>
						</div>
					</div>


				</div>

			</div>
			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_transport_type', $transport_type->id)}}" id="edit-transport-type-button-{{$transport_type->id}}" name="edit-transport-type-button-{{$transport_type->id}}"
							class="btn btn-success edit-transport-type-button">Измени ги податоците</a>
							<button id="delete-transport-type-button-{{ $transport_type->id }}" type="submit" data-toggle="modal" data-target="#delete-transport-type-popup"
								name="delete-transport-type-button" class="btn btn-danger delete-transport-type-button"
								{{($volunteers_count) ? ' disabled' : '' }}>Избриши</button>
								{{-- >Избриши</button> --}}
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-transport-type-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-transport-type-form" class="delete-transport-type-form" action="{{ route('admin.transport_types') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го типот на транспорт</h4>
				</div>
				<div id="delete-transport-type-body" class="modal-body delete-transport-type-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите типот на транспорт?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-transport-type-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

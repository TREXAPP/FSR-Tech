@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Kоличини </span>
		@if ($quantity_types->count() > 0)
		<span> ({{$quantity_types->count()}})</span>
		@endif
		<a href="{{route('admin.new_quantity_type')}}" id="new-quantity-type-button" name="new-quantity-type-button"
		class="btn btn-success new-quantity-type-button"><i class="fa fa-plus"></i>Додади нова количина</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-universal-access"></i> Храна</a>
		</li>
		<li>
			<a href="#">
				<i class="fa fa-universal-access"></i> Количини</a>
		</li>
		<li>
			<a href="{{route('admin.quantity_types')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content quantity-types-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($quantity_types as $quantity_type)



	<!-- Default box -->
<div class="section-wrapper quantity-types-section-wrapper col-md-6">
<div id="quantitytypebox{{$quantity_type->id}}" name="quantitytypebox{{$quantity_type->id}}"></div>
	<div class="admin-quantity-type-box box quantity-type-box two-col-layout-box quantity-type-box-{{$quantity_type->id}} {{($quantity_type->id == old('quantity_type_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div class="header-wrapper">
					<div id="quantity-type-name-{{$quantity_type->id}}" class="quantity-type-name">
						<span class="quantity-type-listing-title two-col-layout-listing-title">{{$quantity_type->description}} - {{$quantity_type->name}}</span>
						<br>
						<small>{{$quantity_type->products->count()}} производи</small>
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

				<div id="quantity-type-info-wrapper-{{$quantity_type->id}}" class="quantity-type-info-wrapper two-col-layout-info-wrapper col-md-12">


					<!-- Description -->
					<div id="quantity-type-info-description-{{$quantity_type->id}}" class="row quantity-type-info-description row">
						<div id="quantity-type-info-description-label-{{$quantity_type->id}}" class="quantity-type-info-label quantity-type-info-description-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="quantity-type-info-description-value-{{$quantity_type->id}}" class="quantity-type-info-value quantity-type-info-description-value col-md-8">
							<span><strong>{{$quantity_type->description}}</strong></span>
						</div>
					</div>

					<!-- Name -->
					<div id="quantity-type-info-name-{{$quantity_type->id}}" class="row quantity-type-info-name row">
						<div id="quantity-type-info-name-label-{{$quantity_type->id}}" class="quantity-type-info-label quantity-type-info-name-label col-md-4">
							<span>Кратенка:</span>
						</div>
						<div id="quantity-type-info-name-value-{{$quantity_type->id}}" class="quantity-type-info-value quantity-type-info-name-value col-md-8">
							<span><strong>{{$quantity_type->name}}</strong></span>
						</div>
					</div>



				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_quantity_type', $quantity_type->id)}}" id="edit-quantity-type-button-{{$quantity_type->id}}" name="edit-quantity-type-button-{{$quantity_type->id}}"
							class="btn btn-success edit-quantity-type-button">Измени ги податоците</a>
							<button id="delete-quantity-type-button-{{ $quantity_type->id }}" type="submit" data-toggle="modal" data-target="#delete-quantity-type-popup"
								name="delete-quantity-type-button" class="btn btn-danger delete-quantity-type-button"
								{{($quantity_type->products->count()) ? ' disabled' : '' }}>Избриши ја количината</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-quantity-type-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-quantity-type-form" class="delete-quantity-type-form" action="{{ route('admin.quantity_types') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши ја количината</h4>
				</div>
				<div id="delete-quantity-type-body" class="modal-body delete-quantity-type-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да ја избришите количината?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-quantity-type-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>


</section>
<!-- /.content -->

@endsection

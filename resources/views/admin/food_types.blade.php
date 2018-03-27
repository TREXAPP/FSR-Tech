@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Категории на храна </span>
		@if ($food_types->count() > 0)
		<span> ({{$food_types->count()}})</span>
		@endif
		<a href="{{route('admin.new_food_type')}}" id="new-food-type-button" name="new-food-type-button"
		class="btn btn-success new-food-type-button"><i class="fa fa-plus"></i>Додади нова категорија</a>
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
				<i class="fa fa-universal-access"></i> Категории</a>
		</li>
		<li>
			<a href="{{route('admin.food_types')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content food-types-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($food_types as $food_type)



	<!-- Default box -->
<div class="section-wrapper food-types-section-wrapper col-md-6">
<div id="foodtypebox{{$food_type->id}}" name="foodtypebox{{$food_type->id}}"></div>
	<div class="admin-food-type-box box food-type-box two-col-layout-box food-type-box-{{$food_type->id}} {{($food_type->id == old('food_type_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="food-type-image-preview-{{$food_type->id}}" class="food-type-image-preview two-col-layout-image-preview">
					@if ($food_type->image_id)
						<img class="img-rounded" alt="{{$food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($food_type->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
					@endif
				</div>
				<div class="header-wrapper">
					<div id="food-type-name-{{$food_type->id}}" class="food-type-name">
						<span class="food-type-listing-title two-col-layout-listing-title">{{$food_type->name}} ({{$food_type->products->count()}})</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="food-type-image-wrapper-{{$food_type->id}}" class="food-type-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($food_type->image_id)
										<img class="img-rounded" alt="{{$food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($food_type->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
									@endif

				</div>

				<div id="food-type-info-wrapper-{{$food_type->id}}" class="food-type-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- Name -->
					<div id="food-type-info-name-{{$food_type->id}}" class="row food-type-info-name row">
						<div id="food-type-info-name-label-{{$food_type->id}}" class="food-type-info-label food-type-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="food-type-info-name-value-{{$food_type->id}}" class="food-type-info-value food-type-info-name-value col-md-8">
							<span><strong>{{$food_type->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="food-type-info-description-{{$food_type->id}}" class="row food-type-info-description row">
						<div id="food-type-info-description-label-{{$food_type->id}}" class="food-type-info-label food-type-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="food-type-info-description-value-{{$food_type->id}}" class="food-type-info-value food-type-info-description-value col-md-8">
							<span><strong>{{$food_type->description}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_food_type', $food_type->id)}}" id="edit-food-type-button-{{$food_type->id}}" name="edit-food-type-button-{{$food_type->id}}"
							class="btn btn-success edit-food-type-button">Измени ги податоците</a>
							<button id="delete-food-type-button-{{ $food_type->id }}" type="submit" data-toggle="modal" data-target="#delete-food-type-popup"
								name="delete-food-type-button" class="btn btn-danger delete-food-type-button"
								{{($food_type->products->count()) ? ' disabled' : '' }}>Избриши ја категоријата</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-food-type-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-food-type-form" class="delete-food-type-form" action="{{ route('admin.food_types') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши ја категоријата</h4>
				</div>
				<div id="delete-food-type-body" class="modal-body delete-food-type-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да ја избришите категоријата?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-food-type-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Производи </span>
		@if ($products->count() > 0)
		<span> ({{$products->count()}})</span>
		@endif
		<a href="{{route('admin.new_product')}}" id="new-product-button" name="new-product-button"
		class="btn btn-success new-product-button"><i class="fa fa-plus"></i>Додади нов производ</a>
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
				<i class="fa fa-universal-access"></i> Производи</a>
		</li>
		<li>
			<a href="{{route('admin.products')}}">
				<i class="fa fa-universal-access"></i> Преглед</a>
		</li>
	</ol>
</section>

<!-- Filter -->
<section class="filter products-filter">
	<div class="filter-wrapper row">
	<form id="products-filter-form" class="products-filter-form" action="{{route('admin.products')}}" method="post">
		<input type="hidden" name="post-type" value="filter" />
		{{csrf_field()}}
		<div class="filter-container col-md-6">
			<div class="filter-label products-filter-label col-md-4">
				<label for="food-types-filter-select">Категорија:</label>
			</div>
			<div class="filter-select products-filter-select col-md-8">
				<select onchange="this.form.submit()" id="food_types_filter_select" class="form-control food-types-filter-select" name="food-types-filter-select" required>
					<option value="">-- Сите --</option>
					@foreach ($food_types as $food_type)
						<option value="{{$food_type->id}}" {{ ($filters['food_type'] == $food_type->id) ? ' selected' : '' }}>{{$food_type->name}}</option>
					@endforeach
				</select>
				@if ($errors->has('food-types-filter-select'))
					<span class="help-block">
						<strong>{{ $errors->first('food-types-filter-select') }}</strong>
					</span>
				@endif
			</div>
		</div>

</form>
</div>
</section>


<!-- Main content -->
<section class="content products-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($products as $product)



	<!-- Default box -->
<div class="section-wrapper products-section-wrapper col-md-6">
<div id="productbox{{$product->id}}" name="productbox{{$product->id}}"></div>
	<div class="admin-product-box box product-box two-col-layout-box product-box-{{$product->id}} {{($product->id == old('product_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="product-image-preview-{{$product->id}}" class="product-image-preview two-col-layout-image-preview">
					@if ($product->food_type->image_id)
						<img class="img-rounded" alt="{{$product->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($product->food_type->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$product->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
					@endif
				</div>
				<div class="header-wrapper">
					<div id="product-name-{{$product->id}}" class="product-name">
						<span class="product-listing-title two-col-layout-listing-title">{{$product->name}}</span>
					</div>
					<div id="product-food-type-{{$product->id}}" class="product-food-type">
						<span class="product-listing-subtitle two-col-layout-listing-subtitle">{{$product->food_type->name}}</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="product-image-wrapper-{{$product->id}}" class="product-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($product->food_type->image_id)
										<img class="img-rounded" alt="{{$product->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($product->food_type->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$product->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
									@endif

				</div>

				<div id="product-info-wrapper-{{$product->id}}" class="product-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- Name -->
					<div id="product-info-name-{{$product->id}}" class="row product-info-name row">
						<div id="product-info-name-label-{{$product->id}}" class="product-info-label product-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="product-info-name-value-{{$product->id}}" class="product-info-value product-info-name-value col-md-8">
							<span><strong>{{$product->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="product-info-description-{{$product->id}}" class="row product-info-description row">
						<div id="product-info-description-label-{{$product->id}}" class="product-info-label product-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="product-info-description-value-{{$product->id}}" class="product-info-value product-info-description-value col-md-8">
							<span><strong>{{$product->description}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('admin.edit_product', $product->id)}}" id="edit-product-button-{{$product->id}}" name="edit-product-button-{{$product->id}}"
							class="btn btn-success edit-product-button">Измени ги податоците</a>
							<button id="delete-product-button-{{ $product->id }}" type="submit" data-toggle="modal" data-target="#delete-product-popup"
								name="delete-product-button" class="btn btn-danger delete-product-button">Избриши го производот</button>
							</div>
			</div>

		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-product-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-product-form" class="delete-product-form" action="{{ route('admin.products') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го производот</h4>
				</div>
				<div id="delete-product-body" class="modal-body delete-product-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите производот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-product-popup" class="btn btn-danger" value="Избриши" />
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

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

				{{-- <div id="quantity-type-image-preview-{{$quantity_type->id}}" class="quantity-type-image-preview two-col-layout-image-preview">
					@if ($quantity_type->image_id)
						<img class="img-rounded" alt="{{$quantity_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($quantity_type->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$quantity_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
					@endif
				</div> --}}
				<div class="header-wrapper">
					<div id="quantity-type-name-{{$quantity_type->id}}" class="quantity-type-name">
						<span class="quantity-type-listing-title two-col-layout-listing-title">{{$quantity_type->description}} ({{$quantity_type->name}})</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				{{-- <div id="quantity-type-image-wrapper-{{$quantity_type->id}}" class="quantity-type-image-wrapper two-col-layout-image-wrapper col-md-4">


									@if ($quantity_type->image_id)
										<img class="img-rounded" alt="{{$quantity_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($quantity_type->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$quantity_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find(1000)->filename)}}" />
									@endif

				</div> --}}

				<div id="quantity-type-info-wrapper-{{$quantity_type->id}}" class="quantity-type-info-wrapper two-col-layout-info-wrapper col-md-12">

					<!-- Name -->
					<div id="quantity-type-info-name-{{$quantity_type->id}}" class="row quantity-type-info-name row">
						<div id="quantity-type-info-name-label-{{$quantity_type->id}}" class="quantity-type-info-label quantity-type-info-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="quantity-type-info-name-value-{{$quantity_type->id}}" class="quantity-type-info-value quantity-type-info-name-value col-md-8">
							<span><strong>{{$quantity_type->name}}</strong></span>
						</div>
					</div>

					<!-- Description -->
					<div id="quantity-type-info-description-{{$quantity_type->id}}" class="row quantity-type-info-description row">
						<div id="quantity-type-info-description-label-{{$quantity_type->id}}" class="quantity-type-info-label quantity-type-info-description-label col-md-4">
							<span>Опис:</span>
						</div>
						<div id="quantity-type-info-description-value-{{$quantity_type->id}}" class="quantity-type-info-value quantity-type-info-description-value col-md-8">
							<span><strong>{{$quantity_type->description}}</strong></span>
						</div>
					</div>


				</div>

			</div>

			<div class="box-footer">
					<div class="pull-right">
						<a href="#" id="edit-quantity-type-button-{{$quantity_type->id}}" name="edit-quantity-type-button-{{$quantity_type->id}}"
							class="btn btn-success edit-quantity-type-button" disabled>Измени ги податоците</a>
							<button id="delete-quantity-type-button-{{ $quantity_type->id }}" type="submit" data-toggle="modal" data-target="#delete-quantity-type-popup"
								name="delete-quantity-type-button" class="btn btn-danger delete-quantity-type-button" disabled >Избриши ја категоријата</button>
							</div>
			</div>

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
			<form id="delete-volunteer-form" class="delete-volunteer-form" action="{{ route('cso.volunteers') }}" method="post">
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

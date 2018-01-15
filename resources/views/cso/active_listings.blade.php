@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header active-listings-content-header">
	<h1>
		<i class="fa fa-cutlery"></i>
		<span>Активни донации</span>
		@if ($active_listings_no > 0)
		<span> ({{$active_listings_no}})</span>
		@endif
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Примател</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/active_listings">
				<i class="fa fa-cutlery"></i> Активни донации</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content active-listings-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif @if ($errors->any())
	<div class="alert alert-danger">
		Донацијата не беше прифатена успешно. Корегирајте ги грешките и обидете се повторно
		<a href="javascript:document.getElementById('listingbox{{ old('listing_id') }}').scrollIntoView();">
			<button type="button" class="btn btn-default">Иди до донацијата</button>
		</a>
	</div>
	@endif @foreach ($active_listings->get() as $active_listing)
	<?php $quantity_counter = 0; ?> @foreach ($active_listing->listing_offers as $listing_offer)
	<?php if ($listing_offer->offer_status == 'active') {
    $quantity_counter += $listing_offer->quantity;
} ?> @endforeach @if ($active_listing->quantity > $quantity_counter)
	<div id="listingbox{{$active_listing->id}}" name="listingbox{{$active_listing->id}}"></div>
	<!-- Default box -->
	<div class="cso-active-listing-box box listing-box listing-box-{{$active_listing->id}} {{($active_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
				<div class="listing-image">
					{{--
					<img src="../img/avatar5.png" /> --}}
					@if ($active_listing->image_id)
					{{-- <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($active_listing->image_id)->filename}}" --}}
					<img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($active_listing->image_id)->filename)}}"
					/> @elseif ($active_listing->product->food_type->image_id)
					<img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($active_listing->product->food_type->image_id)->filename)}}"
					/> @else
					<img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" /> @endif

				</div>
				<div class="header-wrapper">
					<div id="listing-title-{{$active_listing->id}}" class="listing-title col-xs-12 panel">
						<strong>
							{{$active_listing->product->name}}
						</strong>
					</div>
					<div class="header-elements-wrapper">
						<div class="col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Истекува за:</span>

							<span class="col-xs-12" id="expires-in-{{$active_listing->id}}">
								<strong>{{Carbon::parse($active_listing->date_expires)->diffForHumans()}}</strong>
							</span>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Достапна количина:</span>
							<span class="col-xs-12" id="quantity-offered-{{$active_listing->id}}">
								<strong>{{$active_listing->quantity - $quantity_counter}} {{$active_listing->quantity_type->description}}</strong>
							</span>

						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Локација:</span>
							<span class="col-xs-12" id="donor-location-{{$active_listing->id}}">
								<strong>{{$active_listing->donor->location->name}}</strong>
							</span>

						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Донирано од:</span>
							<span class="col-xs-12" id="donor-info-{{$active_listing->id}}">
								<strong>{{$active_listing->donor->first_name}} {{$active_listing->donor->last_name}} | {{$active_listing->donor->organization->name}}</strong>
							</span>

						</div>
					</div>
				</div>
				<div class="box-tools pull-right">
						<i class="fa fa-caret-down"></i>
				</div>
			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div class="row">
					<div class="col-md-4 col-sm-6 listing-pick-up-time ">
						<span class="col-xs-12">Време за подигнување:</span>
						<span class="col-xs-12" id="pickup-time-{{$active_listing->id}}">
							<strong>од {{Carbon::parse($active_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($active_listing->pickup_time_to)->format('H:i')}}
								часот
							</strong>
						</span>
					</div>
					<div class="col-md-3 col-sm-6 listing-food-type ">
						<span class="col-xs-12">Тип на храна:</span>
						<span class="col-xs-12" id="food-type-{{$active_listing->id}}">
							<strong>{{$active_listing->product->food_type->name}}</strong>
						</span>
					</div>
					<div class="col-md-5 col-sm-12 listing-description">
						@if ($active_listing->description)
						<span class="col-xs-12">Опис:</span>
						<span class="col-xs-12" id="description-{{$active_listing->id}}">
							<strong>{{$active_listing->description}}</strong>
						</span>
						@endif
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 listing-input-wrapper">
						<div class="panel col-xs-12" style="text-align: center;">Пополнете точно и кликнете на Внеси</div>
						<div class="col-xs-12 form-group {{ ((old('listing_id') == $active_listing->id) && ($errors->has('quantity'))) ? 'has-error' : '' }} row">
							{{--
							<span class="col-xs-12">Потребна количина:</span> --}}
							<label class="col-sm-6" for="quantity-needed">Потребна количина:</label>
							<span class="col-sm-6">
								<input id="quantity-needed-{{$active_listing->id}}" type="number" min="0" max="{{$active_listing->quantity - $quantity_counter}}"
								 step="0.1" name="quantity-needed" class="form-control quantity-needed-input" value="{{ ($active_listing->id == old('listing_id')) ? old('quantity') : $active_listing->quantity - $quantity_counter }}">
								<span id="quantity-type-inside-{{$active_listing->id}}" class="quantity-type-inside">{{$active_listing->quantity_type->description}}</span>
							</span>
							@if ((old('listing_id') == $active_listing->id) && ($errors->has('quantity')))
							<span class="help-block listing-input-help-block pull-right">
								<strong>{{ $errors->first('quantity') }}</strong>
							</span>
							@endif
						</div>
					</div>
					<div id="listing-pickup-volunteer-{{$active_listing->id}}" class="col-md-6 listing-pickup-volunteer">
						<div class="panel col-xs-12" style="text-align: center;">Волонтер за подигнување</div>
						<div id="pickup-volunteer-wrapper-{{$active_listing->id}}" class="col-xs-12 form-group {{ ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer'))) ? 'has-error' : '' }} row">
							<span class="col-sm-6">

								<select id="pickup-volunteer-{{$active_listing->id}}" class="pickup-volunteer-name form-control" name="pickup-volunteer">
									<option value="">-- Избери --</option>
									@foreach ($volunteers as $volunteer)
									<option value="{{$volunteer->id}}" {{ ((old('listing_id')== $active_listing->id) && (old('volunteer') == $volunteer->id)) ? ' selected' : '' }}>{{$volunteer->first_name}} {{$volunteer->last_name}}</option>
									@endforeach
								</select>
							</span>
							<span class="col-sm-6">
								<button id="add-volunteer-button-{{$active_listing->id}}" type="button" name="add-volunteer-button-{{$active_listing->id}}"
								 class="btn btn-success add-volunteer-button" data-toggle="modal" data-target="#add-volunteer-popup">Додади волонтер</button>
							</span>
							@if ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer')))
							<span class="help-block listing-input-help-block">
								<strong>{{ $errors->first('volunteer') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer text-center">
				<button type="button" id="listing-submit-button-{{$active_listing->id}}" name="listing-submit-button-{{$active_listing->id}}"
				 class="btn btn-primary btn-lg listing-submit-button" data-toggle="modal" data-target="#confirm-listing-popup">Прифати</button>
			</div>
		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->



	@endif @endforeach

	<!-- Confirm listing Modal -->
	<div id="confirm-listing-popup" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<form id="listing-confirm-form" class="listing-confirm-form" action="{{ route('cso.active_listings') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 id="popup-title" class="modal-title popup-title"></h4>
					</div>
					<div id="listing-confirm-body" class="modal-body listing-confirm-body">
						<!-- Form content-->
						<h5 id="popup-info" class="popup-info row italic">
							Проверете ги добро податоците и ако е во ред потврдете:
						</h5>
						<div id="popup-quantity-needed" class="popup-quantity-needed popup-element row">
							<div class="popup-quantity-needed-label col-xs-6">
								<span class="pull-right popup-element-label">Потребна количина:</span>
							</div>
							<div id="popup-quantity-needed-value" class="popup-quantity-needed-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-expires-in" class="popup-expires-in popup-element row">
							<div class="popup-expires-in-label col-xs-6">
								<span class="pull-right popup-element-label">Истекува за:</span>
							</div>
							<div id="popup-expires-in-value" class="popup-expires-in-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-pickup-time" class="popup-pickup-time popup-element row">
							<div class="popup-pickup-time-label col-xs-6">
								<span class="pull-right popup-element-label">Време на подигнување:</span>
							</div>
							<div id="popup-pickup-time-value" class="popup-pickup-time-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-location" class="popup-location popup-element row">
							<div class="popup-location-label col-xs-6">
								<span class="pull-right popup-element-label">Локација:</span>
							</div>
							<div id="popup-location-value" class="popup-location-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-volunteer" class="popup-volunteer popup-element row">
							<div class="popup-volunteer-label col-xs-6">
								<span class="pull-right popup-element-label">Волонтер:</span>
							</div>
							<div id="popup-volunteer-value" class="popup-volunteer-value popup-element-value col-xs-6">
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<input type="submit" name="submit-listing-popup" class="btn btn-primary" value="Прифати" />
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
								<input id="first_name" type="text" class="form-control" name="first_name" value="" style="text-align: center;"  required>
								<span id="first-name-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- last name -->
						<div id="last-name-form-group" class="form-group row">
							<label for="last_name" class="col-md-2 col-md-offset-2 control-label">Презиме:</label>
							<div class="col-md-6">
								<input id="last_name" type="text" class="form-control" name="last_name" value="" style="text-align: center;" required>
								<span id="last-name-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- email -->
						<div id="email-form-group" class="form-group row">
							<label for="email" class="col-md-2 col-md-offset-2 control-label">Емаил:</label>
							<div class="col-md-6">
								<input id="email" type="email" class="form-control" name="email" value="" style="text-align: center;" required >
								<span id="email-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- email -->
						<div id="phone-form-group" class="form-group row">
							<label for="phone" class="col-md-2 col-md-offset-2 control-label">Контакт:</label>
							<div class="col-md-6">
								<input id="phone" type="text" class="form-control" name="phone" value="" style="text-align: center;" required>
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

@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->


<section class="content-header donor-listings-content-header">
	<h1>
		<i class="fa fa-cutlery"></i>
		<span>Достапни донации</span>
		@if ($donor_listings_no > 0)
		<span> ({{$donor_listings_no}})</span>
		@endif
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Хаб</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/donor_listings">
				<i class="fa fa-cutlery"></i> Достапни донации</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content donor-listings-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>

	@endif
	@if ($errors->any())
	<div class="alert alert-danger">
		Донацијата не беше прифатена успешно. Корегирајте ги грешките и обидете се повторно
		<a href="javascript:document.getElementById('listingbox{{ old('listing_id') }}').scrollIntoView();">
			<button type="button" class="btn btn-default">Иди до донацијата</button>
		</a>
	</div>
	@endif

	@foreach ($donor_listings->get() as $donor_listing)
		<?php $quantity_counter = 0; ?> @foreach ($donor_listing->hub_listing_offers as $hub_listing_offer)
		<?php if ($hub_listing_offer->status == 'active') {
    $quantity_counter += $hub_listing_offer->quantity;
} ?>
	@endforeach

	@if ($donor_listing->quantity > $quantity_counter)
	<div id="listingbox{{$donor_listing->id}}" name="listingbox{{$donor_listing->id}}"></div>
	<!-- Default box -->
	<div id="active-listing-{{$donor_listing->id}}" class="hub-active-listing-box box listing-box listing-box-{{$donor_listing->id}} {{($donor_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
				<div class="listing-image">
					@if ($donor_listing->image_id)
					{{-- <img class="img-rounded" alt="{{$donor_listing->product->food_type->name}}" src="../../storage{{config('app.upload_path') . '/' . FSR\File::find($donor_listing->image_id)->filename}}" --}}
					<img class="img-rounded" alt="{{$donor_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($donor_listing->image_id)->filename)}}"
					/> @elseif ($donor_listing->product->food_type->image_id)
					<img class="img-rounded" alt="{{$donor_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($donor_listing->product->food_type->image_id)->filename)}}"
					/> @else
					<img class="img-rounded" alt="{{$donor_listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" /> @endif

				</div>
				<div class="header-wrapper">
					<div id="listing-title-{{$donor_listing->id}}" class="listing-title col-xs-12 panel">
						<strong>{{$donor_listing->product->food_type->name}} | {{$donor_listing->product->name}}</strong>
					</div>
					<div class="header-elements-wrapper">
						<div class="listing-info-box col-md-3 col-sm-5 col-xs-12">
							<span class="col-xs-12">Достапна на платформата уште:</span>

							<span class="col-xs-12" id="expires-in-{{$donor_listing->id}}">
								<strong>{{Carbon::parse($donor_listing->date_expires)->diffForHumans()}}</strong>
							</span>
						</div>
						<div class="listing-info-box col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Достапна количина:</span>
							<span class="col-xs-12" id="quantity-offered-{{$donor_listing->id}}">
								<strong>{{$donor_listing->quantity - $quantity_counter}} {{$donor_listing->quantity_type->description}}</strong>
							</span>

						</div>
						<div class="listing-info-box col-md-2 col-sm-5 col-xs-12">
							<span class="col-xs-12">Локација:</span>
							<span class="col-xs-12" id="donor-location-{{$donor_listing->id}}">
								<strong>{{$donor_listing->donor->location->name}}</strong>
							</span>

						</div>
						<div class="listing-info-box col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Донирано од:</span>
							<span class="col-xs-12" id="donor-info-{{$donor_listing->id}}">
								<strong>{{$donor_listing->donor->first_name}} {{$donor_listing->donor->last_name}} | {{$donor_listing->donor->organization->name}}</strong>
							</span>

						</div>
					</div>
				</div>
				<div class="box-tools pull-right">
						{{-- <i class="fa fa-caret-down"></i> --}}
						<span class="add-more">Повеќе...</span>
				</div>
			</a>
		</div>
		@if (Auth::user()->email_confirmed)
			<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div class="row">
					<div class="listing-info-box col-md-3 col-sm-6 listing-pick-up-time ">
						<span class="col-xs-12">Време за подигнување:</span>
						<span class="col-xs-12" id="pickup-time-{{$donor_listing->id}}">
							<strong>од {{Carbon::parse($donor_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($donor_listing->pickup_time_to)->format('H:i')}}
								часот
							</strong>
						</span>
					</div>
					<div class="listing-info-box col-md-3 col-sm-6 listing-food-type ">
						<span class="col-xs-12">Рок на траење на храната:</span>
						<span class="col-xs-12" id="food-type-{{$donor_listing->id}}">
							<strong>{{Carbon::parse($donor_listing->sell_by_date)->format('d.m.Y')}}</strong>
						</span>
					</div>
					<div class="listing-info-box col-md-3 col-sm-12 listing-description">
						@if ($donor_listing->description)
						<span class="col-xs-12">Опис:</span>
						<span class="col-xs-12" id="description-{{$donor_listing->id}}">
							<strong>{{$donor_listing->description}}</strong>
						</span>
						@endif
					</div>
					<div class="listing-info-box col-md-3 col-sm-12 listing-donor-info-button">
						<button data-toggle="modal" data-target="#donor-details-popup" id="donor-details-{{$donor_listing->id}}"
						 			type="button" class="btn btn-success donor-details" name="button">Информации за донаторот</button>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 col-xs-12 listing-input-wrapper">
						<div class="panel col-xs-12" style="text-align: center;">Пополнете точно и кликнете на Прифати</div>
						<div class="col-xs-12 form-group {{ ((old('listing_id') == $donor_listing->id) && ($errors->has('quantity'))) ? 'has-error' : '' }} row">
							{{--
							<span class="col-xs-12">Потребна количина:</span> --}}
							<label class="col-sm-6" for="quantity-needed">Потребна количина:</label>
							<span class="col-sm-6">
								<input id="quantity-needed-{{$donor_listing->id}}" type="number" min="0" max="{{$donor_listing->quantity - $quantity_counter}}"
								 step="0.1" name="quantity-needed" class="form-control quantity-needed-input" value="{{ ($donor_listing->id == old('listing_id')) ? old('quantity') : $donor_listing->quantity - $quantity_counter }}">
								<span id="quantity-type-inside-{{$donor_listing->id}}" class="quantity-type-inside">{{$donor_listing->quantity_type->description}}</span>
							</span>
							@if ((old('listing_id') == $donor_listing->id) && ($errors->has('quantity')))
							<span class="help-block listing-input-help-block pull-right">
								<strong>{{ $errors->first('quantity') }}</strong>
							</span>
							@endif
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer text-center">
				<button type="button" id="listing-submit-button-{{$donor_listing->id}}" name="listing-submit-button-{{$donor_listing->id}}"
				 class="btn btn-primary btn-lg listing-submit-button" data-toggle="modal" data-target="#confirm-listing-popup">Прифати</button>
			</div>
		</div>
		@endif
		<!-- /.box-footer-->
	</div>
	<!-- /.box -->



	@endif
	<div id="hidden-first-name-{{$donor_listing->id}}" class="hidden-first-name hidden">{{$donor_listing->donor->first_name}}</div>
	<div id="hidden-last-name-{{$donor_listing->id}}" class="hidden-last-name hidden">{{$donor_listing->donor->last_name}}</div>
	<div id="hidden-email-{{$donor_listing->id}}" class="hidden-email hidden">{{$donor_listing->donor->email}}</div>
	<div id="hidden-organization-{{$donor_listing->id}}" class="hidden-organization hidden">{{$donor_listing->donor->organization->name}}</div>
	<div id="hidden-phone-{{$donor_listing->id}}" class="hidden-phone hidden">{{$donor_listing->donor->phone}}</div>
	<div id="hidden-address-{{$donor_listing->id}}" class="hidden-address hidden">{{$donor_listing->donor->address}}</div>

@endforeach

	<!-- Confirm listing Modal -->
	<div id="confirm-listing-popup" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<form id="listing-confirm-form" class="listing-confirm-form" action="{{ route('hub.donor_listings') }}" method="post">
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
								<span class="pull-right popup-element-label">Достапна на платформата уште:</span>
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

						<hr>
						<h5 id="popup-donor-info" class="popup-info popup-donor-info row italic">
							Податоци за донаторот:
						</h5>

						<div id="popup-donor-first-name" class="popup-donor-first-name popup-element row">
							<div class="popup-donor-first-name-label col-xs-6">
								<span class="pull-right popup-element-label">Име:</span>
							</div>
							<div id="popup-donor-first-name-value" class="popup-donor-first-name-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-donor-last-name" class="popup-donor-last-name popup-element row">
							<div class="popup-donor-last-name-label col-xs-6">
								<span class="pull-right popup-element-label">Презиме:</span>
							</div>
							<div id="popup-donor-last-name-value" class="popup-donor-last-name-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-donor-email" class="popup-donor-email popup-element row">
							<div class="popup-donor-email-label col-xs-6">
								<span class="pull-right popup-element-label">Email:</span>
							</div>
							<div id="popup-donor-email-value" class="popup-donor-email-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-donor-organization" class="popup-donor-organization popup-element row">
							<div class="popup-donor-organization-label col-xs-6">
								<span class="pull-right popup-element-label">Организација:</span>
							</div>
							<div id="popup-donor-organization-value" class="popup-donor-organization-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-donor-phone" class="popup-donor-phone popup-element row">
							<div class="popup-donor-phone-label col-xs-6">
								<span class="pull-right popup-element-label">Телефон:</span>
							</div>
							<div id="popup-donor-phone-value" class="popup-donor-phone-value popup-element-value col-xs-6">
							</div>
						</div>

						<div id="popup-donor-address" class="popup-donor-address popup-element row">
							<div class="popup-donor-address-label col-xs-6">
								<span class="pull-right popup-element-label">Адреса:</span>
							</div>
							<div id="popup-donor-address-value" class="popup-donor-address-value popup-element-value col-xs-6">
							</div>
						</div>

					</div>
					{{-- <hr class="rules-and-regulations-hr"> --}}
					<hr>
					<div class="form-group row">
						<div class="col-xs-8 col-xs-offset-2">
							<label>
								<input type="checkbox" required /> Ги прифаќам <a href="https://drive.google.com/file/d/1q4BI9Vxl0P2742mgPN8tTESJXDssDZlT/view" target="_blank">Правилата и прописите</a>
							</label>
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

	<!-- Donor details Modal -->
	<div id="donor-details-popup" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 id="details-popup-title" class="modal-title popup-title details-popup-title">Информации за донаторот</h4>
					</div>
					<div id="donor-info-body" class="modal-body donor-info-body">
						<!-- Form content-->
						{{-- <h5 id="popup-info" class="popup-info row italic">
						</h5> --}}
						<div id="details-popup-first-name" class="details-popup-first-name popup-element row">
							<div class="details-popup-first-name-label col-xs-6">
								<span class="pull-right popup-element-label">Име:</span>
							</div>
							<div id="details-popup-first-name-value" class="details-popup-first-name popup-element-value col-xs-6">
							</div>
						</div>

						<div id="details-popup-last-name" class="details-popup-last-name popup-element row">
							<div class="details-popup-last-name-label col-xs-6">
								<span class="pull-right popup-element-label">Презиме:</span>
							</div>
							<div id="details-popup-last-name-value" class="details-popup-pickup-last-name popup-element-value col-xs-6">
							</div>
						</div>

						<div id="details-popup-organization" class="details-popup-organization popup-element row">
							<div class="details-popup-organization-label col-xs-6">
								<span class="pull-right popup-element-label">Организација:</span>
							</div>
							<div id="details-popup-organization-value" class="details-popup-organization popup-element-value col-xs-6">
							</div>
						</div>

						<div id="details-popup-phone" class="details-popup-phone popup-element row">
							<div class="details-popup-phone-label col-xs-6">
								<span class="pull-right popup-element-label">Телефон:</span>
							</div>
							<div id="details-popup-phone-value" class="details-popup-phone popup-element-value col-xs-6">
							</div>
						</div>

						<div id="details-popup-address" class="details-popup-address popup-element row">
							<div class="details-popup-address-label col-xs-6">
								<span class="pull-right popup-element-label">Адреса:</span>
							</div>
							<div id="details-popup-address-value" class="details-popup-address popup-element-value col-xs-6">
							</div>
						</div>

						<h5>
						</h5>
					<div class="modal-footer">
						{{-- <input type="submit" name="submit-listing-popup" class="btn btn-primary" value="Прифати" /> --}}
						<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
					</div>
			</div>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

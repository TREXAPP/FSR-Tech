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
						<div class="panel col-xs-12" style="text-align: center;">Внесете потребна количина за прифаќање</div>
						<div class="col-xs-12 form-group {{ ((old('listing_id') == $donor_listing->id) && ($errors->has('quantity'))) ? 'has-error' : '' }} row">
							<label class="col-sm-6" for="quantity-needed">Количина за прифаќање:</label>
							<span class="col-sm-6">
								<input id="hub-quantity-needed-{{$donor_listing->id}}" type="number" min="0" max="{{$donor_listing->quantity - $quantity_counter}}"
								 step="0.1" name="quantity-needed" class="form-control hub-quantity-needed-input" value="{{ ($donor_listing->id == old('listing_id')) ? old('quantity') : $donor_listing->quantity - $quantity_counter }}">
								<span id="quantity-type-inside-{{$donor_listing->id}}" class="quantity-type-inside">{{$donor_listing->quantity_type->description}}</span>
							</span>
							@if ((old('listing_id') == $donor_listing->id) && ($errors->has('quantity')))
							<span class="help-block listing-input-help-block pull-right">
								<strong>{{ $errors->first('quantity') }}</strong>
							</span>
							@endif
						</div>
					</div>
					<div class="col-md-6 col-xs-12 listing-input-wrapper">
						<div class="col-xs-12 ">
							<label>
								<input type="checkbox" id="checkbox-reposted-{{$donor_listing->id}}" class="checkbox-reposted">
								<span>Креирај објава за прифатената донација</span>
							</label>
						</div>
						<div id="reposted-controls-{{$donor_listing->id}}" style="display: none;">
							<!-- quantity reposted -->
							<div id="quantity-reposted-wrapper-{{$donor_listing->id}}" class="col-xs-12 form-group quantity-reposted-wrapper {{ ((old('listing_id') == $donor_listing->id) && ($errors->has('quantity-reposted'))) ? 'has-error' : '' }} row">
								<label class="col-sm-6" for="quantity-reposted">Количина за објава:</label>
								<span class="col-sm-6">
									<input id="quantity-reposted-{{$donor_listing->id}}" type="number" min="0" max="{{$donor_listing->quantity - $quantity_counter}}"
									step="0.1" name="quantity-reposted" class="form-control hub-quantity-reposted-input" value="{{ ($donor_listing->id == old('listing_id')) ? old('quantity-reposted') : $donor_listing->quantity - $quantity_counter }}">
									<span id="quantity-type-inside-{{$donor_listing->id}}" class="quantity-type-inside">{{$donor_listing->quantity_type->description}}</span>
								</span>
								@if ((old('listing_id') == $donor_listing->id) && ($errors->has('quantity-reposted')))
								<span class="help-block listing-input-help-block pull-right">
									<strong>{{ $errors->first('quantity-reposted') }}</strong>
								</span>
								@endif
							</div>

							<!-- expires_in-reposted -->
							<div id="expires_in-reposted-wrapper-{{$donor_listing->id}}" class=" col-xs-12 expires_in-reposted-wrapper form-group{{ $errors->has('expires_in-reposted') ? ' has-error' : '' }} row">
								<label for="expires_in-reposted" class="col-sm-6">Донацијата ќе биде достапна</label>
								<div class="col-sm-6">
									<div class="col-xs-6" style="padding-left: 0px;">
										<input id="expires_in-reposted-{{$donor_listing->id}}" type="number" name="expires_in-reposted" min="0" max="99999999" step="1"  class="form-control expires_id-reposted"
												name="expires_in-reposted" value="{{old('expires_in-reposted')}}" style="text-align: center;" required >
									</div>
									<div class="col-xs-6"  style="padding-right: 0px;">
										<select id="time_type-reposted-{{$donor_listing->id}}" class="form-control time_type-reposted" name="time_type-reposted">
											<option value="hours" {{ (old('time_type-reposted') == "hours") ? ' selected' : ''}}>часа</option>
											<option value="days" {{(old('time_type-reposted')) ? ((old('time_type-reposted') == "days") ? ' selected' : '') : ' selected'}}>дена</option>
											<option value="weeks" {{ (old('time_type-reposted') == "weeks") ? ' selected' : ''}}>недели</option>
										</select>
									</div>
									@if ($errors->has('expires_in-reposted'))
									<span class="help-block col-xs-12" style="padding-left: 0px;">
										<strong>{{ $errors->first('expires_in-reposted') }}</strong>
									</span>
									@endif
								</div>
							</div>

							<!-- Description-reposted -->
							<div class="col-xs-12 form-group{{ $errors->has('description-reposted') ? ' has-error' : '' }} row">
								<label for="description-reposted" class="col-md-2 control-label">Опис</label>

								<div class="col-md-10">
									<textarea rows="4" form="new_hub_listing_form" id="description-reposted-{{$donor_listing->id}}" class="form-control description-reposted"
											placeholder="Подетално опишете ја состојбата и типот на храната"
											name="description-reposted" >{{ old('description-reposted') }}</textarea>
									@if ($errors->has('description-reposted'))
									<span class="help-block">
										<strong>{{ $errors->first('description-reposted') }}</strong>
									</span>
									@endif
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
			<div class="box-footer text-center">
				<button type="button" id="hub-listing-submit-button-{{$donor_listing->id}}" name="hub-listing-submit-button-{{$donor_listing->id}}"
				 class="btn btn-primary btn-lg hub-listing-submit-button">Прифати</button>
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
	<div id="hidden-location-{{$donor_listing->id}}" class="hidden-location hidden">{{$donor_listing->donor->location->name}}</div>

	<div id="hidden-product-id-{{$donor_listing->id}}" class="hidden-product-id hidden">{{$donor_listing->product_id}}</div>
	<div id="hidden-food-type-{{$donor_listing->id}}" class="hidden-food-type hidden">{{$donor_listing->product->food_type_id}}</div>
	<div id="hidden-quantity-type-id-{{$donor_listing->id}}" class="hidden-quantity-type-id hidden">{{$donor_listing->quantity_type_id}}</div>
	<div id="hidden-sell-by-date-{{$donor_listing->id}}" class="hidden-sell-by-date hidden">{{$donor_listing->sell_by_date}}</div>

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
						<div id="accept-listing-info-modal">
							<div id="popup-quantity-needed" class="popup-quantity-needed popup-element row">
								<div class="popup-hub-quantity-needed-label col-xs-6">
									<span class="pull-right popup-element-label">Потребна количина:</span>
								</div>
								<div id="popup-hub-quantity-needed-value" class="popup-hub-quantity-needed-value popup-element-value col-xs-6">
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
								<div id="popup-donor-location-value" class="popup-donor-location-value popup-element-value col-xs-6">
								</div>
							</div>
						</div>

						<hr>
						<div id="donor-info-modal">
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
						<div id="reposted-info-modal">
							<h5 id="popup-reposted-info" class="popup-info popup-reposted-info row italic">
								Донацијата ќе биде објавена со следниве податоци:
							</h5>

							<div id="popup-reposted-product" class="popup-reposted-product popup-element row">
								<div class="popup-reposted-product-label col-xs-6">
									<span class="pull-right popup-element-label">Производ:</span>
								</div>
								<div id="popup-reposted-product-value" class="popup-reposted-product-value popup-element-value col-xs-6">
								</div>
							</div>

							<div id="popup-reposted-available" class="popup-reposted-available popup-element row">
								<div class="popup-reposted-available-label col-xs-6">
									<span class="pull-right popup-element-label">Донацијата ќе биде достапна:</span>
								</div>
								<div id="popup-reposted-available-value" class="popup-reposted-available-value popup-element-value col-xs-6">
								</div>
							</div>

							<div id="popup-reposted-quantity" class="popup-reposted-quantity popup-element row">
								<div class="popup-reposted-quantity-label col-xs-6">
									<span class="pull-right popup-element-label">Количина за објава:</span>
								</div>
								<div id="popup-reposted-quantity-value" class="popup-reposted-quantity-value popup-element-value col-xs-6">
								</div>
							</div>

							<div id="popup-reposted-description" class="popup-reposted-description popup-element row">
								<div class="popup-reposted-description-label col-xs-6">
									<span class="pull-right popup-element-label">Опис:</span>
								</div>
								<div id="popup-reposted-description-value" class="popup-reposted-description-value popup-element-value col-xs-6">
								</div>
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
						<h4 id="donor-details-popup-title" class="modal-title popup-title donor-details-popup-title">Информации за донаторот</h4>
					</div>
					<div id="donor-info-body" class="modal-body donor-info-body">
						<!-- Form content-->
						{{-- <h5 id="popup-info" class="popup-info row italic">
						</h5> --}}
						<div id="donor-details-popup-first-name" class="donor-details-popup-first-name popup-element row">
							<div class="donor-details-popup-first-name-label col-xs-6">
								<span class="pull-right popup-element-label">Име:</span>
							</div>
							<div id="donor-details-popup-first-name-value" class="donor-details-popup-first-name popup-element-value col-xs-6">
							</div>
						</div>

						<div id="donor-details-popup-last-name" class="donor-details-popup-last-name popup-element row">
							<div class="donor-details-popup-last-name-label col-xs-6">
								<span class="pull-right popup-element-label">Презиме:</span>
							</div>
							<div id="donor-details-popup-last-name-value" class="donor-details-popup-pickup-last-name popup-element-value col-xs-6">
							</div>
						</div>

						<div id="donor-details-popup-organization" class="donor-details-popup-organization popup-element row">
							<div class="donor-details-popup-organization-label col-xs-6">
								<span class="pull-right popup-element-label">Организација:</span>
							</div>
							<div id="donor-details-popup-organization-value" class="donor-details-popup-organization popup-element-value col-xs-6">
							</div>
						</div>

						<div id="donor-details-popup-phone" class="donor-details-popup-phone popup-element row">
							<div class="donor-details-popup-phone-label col-xs-6">
								<span class="pull-right popup-element-label">Телефон:</span>
							</div>
							<div id="donor-details-popup-phone-value" class="donor-details-popup-phone popup-element-value col-xs-6">
							</div>
						</div>

						<div id="donor-details-popup-address" class="donor-details-popup-address popup-element row">
							<div class="donor-details-popup-address-label col-xs-6">
								<span class="pull-right popup-element-label">Адреса:</span>
							</div>
							<div id="donor-details-popup-address-value" class="donor-details-popup-address popup-element-value col-xs-6">
							</div>
						</div>

						<div id="donor-details-popup-location" class="donor-details-popup-location popup-element row">
							<div class="donor-details-popup-location-label col-xs-6">
								<span class="pull-right popup-element-label">Локација:</span>
							</div>
							<div id="donor-details-popup-location-value" class="donor-details-popup-location popup-element-value col-xs-6">
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

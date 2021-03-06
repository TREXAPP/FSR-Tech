@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header active-listings-content-header">
	<h1>
		<i class="fa fa-cutlery"></i>
		<span>Достапни донации</span>
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
				<i class="fa fa-cutlery"></i> Достапни донации</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content active-listings-content">

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

	@foreach ($active_listings->get() as $active_listing)
		<?php $quantity_counter = 0; ?> @foreach ($active_listing->listing_offers as $listing_offer)
		<?php if ($listing_offer->offer_status == 'active') {
    $quantity_counter += $listing_offer->quantity;
} ?>
	@endforeach

	@if ($active_listing->quantity > $quantity_counter)
	<div id="listingbox{{$active_listing->id}}" name="listingbox{{$active_listing->id}}"></div>
	<!-- Default box -->
	<div id="active-listing-{{$active_listing->id}}" class="cso-active-listing-box box listing-box listing-box-{{$active_listing->id}} {{($active_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">
				<div class="listing-image">
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
						<strong>{{$active_listing->product->food_type->name}} | {{$active_listing->product->name}}</strong>
					</div>
					<div class="header-elements-wrapper">
						<div class="listing-info-box col-md-3 col-sm-5 col-xs-12">
							<span class="col-xs-12">Достапна на платформата уште:</span>

							<span class="col-xs-12" id="expires-in-{{$active_listing->id}}">
								<strong>{{Carbon::parse($active_listing->date_expires)->diffForHumans()}}</strong>
							</span>
						</div>
						<div class="listing-info-box col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Достапна количина:</span>
							<span class="col-xs-12" id="quantity-offered-{{$active_listing->id}}">
								<strong>{{$active_listing->quantity - $quantity_counter}} {{$active_listing->quantity_type->description}}</strong>
							</span>

						</div>
						<div class="listing-info-box col-md-2 col-sm-5 col-xs-12">
							<span class="col-xs-12">Локација:</span>
							<span class="col-xs-12" id="donor-location-{{$active_listing->id}}">
								<strong>{{$active_listing->donor->location->name}}</strong>
							</span>

						</div>
						<div class="listing-info-box col-md-3 col-sm-6 col-xs-12">
							<span class="col-xs-12">Донирано од:</span>
							<span class="col-xs-12" id="donor-info-{{$active_listing->id}}">
								<strong>{{$active_listing->donor->first_name}} {{$active_listing->donor->last_name}} | {{$active_listing->donor->organization->name}}</strong>
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
						<span class="col-xs-12" id="pickup-time-{{$active_listing->id}}">
							<strong>од {{Carbon::parse($active_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($active_listing->pickup_time_to)->format('H:i')}}
								часот
							</strong>
						</span>
					</div>
					<div class="listing-info-box col-md-3 col-sm-6 listing-food-type ">
						<span class="col-xs-12">Рок на траење на храната:</span>
						<span class="col-xs-12" id="food-type-{{$active_listing->id}}">
							<strong>{{Carbon::parse($active_listing->sell_by_date)->format('d.m.Y')}}</strong>
						</span>
					</div>
					<div class="listing-info-box col-md-3 col-sm-12 listing-description">
						@if ($active_listing->description)
						<span class="col-xs-12">Опис:</span>
						<span class="col-xs-12" id="description-{{$active_listing->id}}">
							<strong>{{$active_listing->description}}</strong>
						</span>
						@endif
					</div>
					<div class="listing-info-box col-md-3 col-sm-12 listing-donor-info-button">
						<button data-toggle="modal" data-target="#donor-details-popup" id="donor-details-{{$active_listing->id}}"
						 			type="button" class="btn btn-success donor-details" name="button">Информации за донаторот</button>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6 col-xs-12 listing-input-wrapper">
						<div class="panel col-xs-12" style="text-align: center;">Пополнете точно и кликнете на Прифати</div>
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
					<div id="listing-pickup-volunteer-{{$active_listing->id}}" class="col-md-6 col-xs-12 listing-pickup-volunteer">
						<div class="panel" style="text-align: center;">Доставувач</div>
						<div id="pickup-volunteer-wrapper-{{$active_listing->id}}" class="form-group pickup-volunteer-wrapper {{ ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer'))) ? 'has-error' : '' }} row">
							<span class="col-sm-6">

								<select id="pickup-volunteer-{{$active_listing->id}}" class="pickup-volunteer-name form-control" name="pickup-volunteer-{{$active_listing->id}}" required>
									<option value="">-- Избери --</option>
									@foreach ($volunteers as $volunteer)
									<option value="{{$volunteer->id}}"
										{{ ((old('listing_id')== $active_listing->id) && (old('volunteer') == $volunteer->id))
												? ' selected'
												: (($volunteer->is_user && Auth::user()->id == $volunteer->added_by_user_id)
													? ' selected'
													: '')}}>{{$volunteer->first_name}} {{$volunteer->last_name}}{{($volunteer->is_user && Auth::user()->id == $volunteer->added_by_user_id)
																																													? ' (јас)'
																																													: ''}}</option>
									@endforeach
								</select>
							</span>
							<span class="col-sm-6">
								<button id="add-volunteer-button-{{$active_listing->id}}" type="button" name="add-volunteer-button-{{$active_listing->id}}"
								 class="btn btn-success add-volunteer-button" data-toggle="modal" data-target="#add-volunteer-popup">Додади доставувач</button>
							</span>
							@if ((old('listing_id') == $active_listing->id) && ($errors->has('volunteer')))
							<span class="help-block listing-input-help-block">
								<strong>{{ $errors->first('volunteer') }}</strong>
							</span>
							@endif
						</div>

						<div id="active-listings-volunteer-show-{{$active_listing->id}}"
									class="row {{ (old('listing_id') == $active_listing->id) ? ((old('volunteer')) ? '' : 'hidden') : 'hidden' }} ">
							<div class="hidden" id="volunteer-id-{{$active_listing->id}}"></div>

							<div id="volunteer-image-wrapper-{{$active_listing->id}}" class="volunteer-image-wrapper two-col-layout-image-wrapper col-md-4">
									<img id="volunteer-info-image-{{$active_listing->id}}" class="img-rounded" alt=""
											src="{{ ((old('listing_id') == $active_listing->id) && (old('volunteer')))
												? (($volunteers->find(old('volunteer'))->image_id)
														? url('storage' . config('app.upload_path') . '/' . FSR\File::find($volunteers->find(old('volunteer'))->image_id)->filename)
														: url('img/volunteer.png'))
												: url('img/volunteer.png') }}" />
							</div>

							<div id="volunteer-info-wrapper-{{$active_listing->id}}" class="volunteer-info-wrapper-accepted two-col-layout-info-wrapper col-md-8" >

								<!-- First Name -->
								<div id="volunteer-info-first-name-{{$active_listing->id}}" class="volunteer-info-first-name row">
									<div id="volunteer-info-first-name-label-{{$active_listing->id}}" class="volunteer-info-label volunteer-info-first-name-label col-md-4">
										<span>Име:</span>
									</div>
									<div id="volunteer-info-first-name-value-{{$active_listing->id}}" class="volunteer-info-value volunteer-info-first-name-value col-md-8">
										<span>{{ ((old('listing_id') == $active_listing->id) && (old('volunteer'))) ? $volunteers->find(old('volunteer'))->first_name : '' }}</span>
									</div>
								</div>

								<!-- Last Name -->
								<div id="volunteer-info-last-name-{{$active_listing->id}}" class="volunteer-info-last-name row">
									<div id="volunteer-info-last-name-label-{{$active_listing->id}}" class="volunteer-info-label volunteer-info-last-name-label col-md-4">
										<span>Презиме:</span>
									</div>
									<div id="volunteer-info-last-name-value-{{$active_listing->id}}" class="volunteer-info-value volunteer-info-last-name-value col-md-8">
										<span>{{ ((old('listing_id') == $active_listing->id) && (old('volunteer'))) ? $volunteers->find(old('volunteer'))->last_name : '' }}</span>
									</div>
								</div>

								<!-- Email -->
								<div id="volunteer-info-email-{{$active_listing->id}}" class="volunteer-info-email row">
									<div id="volunteer-info-email-label-{{$active_listing->id}}" class="volunteer-info-label volunteer-info-email-label col-md-4">
										<span>Емаил:</span>
									</div>
									<div id="volunteer-info-email-value-{{$active_listing->id}}" class="volunteer-info-value volunteer-info-email-value col-md-8">
										<span>{{ ((old('listing_id') == $active_listing->id) && (old('volunteer'))) ? $volunteers->find(old('volunteer'))->email : '' }}</span>
									</div>
								</div>

								<!-- Phone -->
								<div id="volunteer-info-phone-{{$active_listing->id}}" class="volunteer-info-phone row">
									<div id="volunteer-info-phone-label-{{$active_listing->id}}" class="volunteer-info-label volunteer-info-phone-label col-md-4">
										<span>Телефон:</span>
									</div>
									<div id="volunteer-info-phone-value-{{$active_listing->id}}" class="volunteer-info-value volunteer-info-phone-value col-md-8">
										<span>{{ ((old('listing_id') == $active_listing->id) && (old('volunteer'))) ? $volunteers->find(old('volunteer'))->phone : '' }}</span>
									</div>
								</div>


							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="box-footer text-center">
				<button type="button" id="listing-submit-button-{{$active_listing->id}}" name="listing-submit-button-{{$active_listing->id}}"
				 class="btn btn-primary btn-lg listing-submit-button" data-toggle="modal" data-target="#confirm-listing-popup">Прифати</button>
			</div>
		</div>
		@endif
		<!-- /.box-footer-->
	</div>
	<!-- /.box -->



	@endif
	<div id="hidden-first-name-{{$active_listing->id}}" class="hidden-first-name hidden">{{$active_listing->donor->first_name}}</div>
	<div id="hidden-last-name-{{$active_listing->id}}" class="hidden-last-name hidden">{{$active_listing->donor->last_name}}</div>
	<div id="hidden-email-{{$active_listing->id}}" class="hidden-email hidden">{{$active_listing->donor->email}}</div>
	<div id="hidden-organization-{{$active_listing->id}}" class="hidden-organization hidden">{{$active_listing->donor->organization->name}}</div>
	<div id="hidden-phone-{{$active_listing->id}}" class="hidden-phone hidden">{{$active_listing->donor->phone}}</div>
	<div id="hidden-address-{{$active_listing->id}}" class="hidden-address hidden">{{$active_listing->donor->address}}</div>

@endforeach

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

						<div id="popup-volunteer" class="popup-volunteer popup-element row">
							<div class="popup-volunteer-label col-xs-6">
								<span class="pull-right popup-element-label">Доставувач:</span>
							</div>
							<div id="popup-volunteer-value" class="popup-volunteer-value popup-element-value col-xs-6">
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
						<h4 id="popup-title" class="modal-title popup-title">Нов Доставувач</h4>
					</div>
					<div id="add-volunteer-body" class="modal-body add-volunteer-body">
						<!-- Form content-->
						<h5 id="popup-info" class="popup-info row italic">
							Внесете ги податоците за доставувачот:
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

						<!-- contact -->
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

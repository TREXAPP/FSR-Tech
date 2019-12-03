@extends('layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header active-listings-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Мои донации</span>
      @if ($hub_listings->count() > 0)
        <span> ({{$hub_listings->count()}})</span>
      @endif
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Хаб</a></li>
      <li><a href="/{{Auth::user()->type()}}/active_listings"><i class="fa fa-cutlery"></i> Мои донации</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content active-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  <!-- Filter -->
  <section class="filter donations-filter">
  	<div class="filter-wrapper row">
    	<form id="donations-filter-form" class="donations-filter-form" action="{{route('hub.active_listings')}}" method="post">
    		<input type="hidden" name="post-type" value="filter" />
    		{{csrf_field()}}
    		<div class="filter-container">
    			<div class="form-group filter-select donations-filter-select col-md-4">
    				<select id="donations_filter_select" class="form-control donations-filter-select" name="donations-filter-select" required>
    					<option value="active" {{($selected_filter == "active") ? "selected" : ""}}>Активни донации</option>
    					<option value="past"  {{($selected_filter == "past") ? "selected" : ""}}>Изминати донации</option>
    				</select>
    			</div>
          <div class="filter_date_from_wrapper form-group col-md-3">
            <div class="filter_label_wrapper col-xs-2">
              <label for="filter_date_from">Од:</label>
            </div>
            <div class="filter_input_wrapper col-xs-10">
              <input id="filter_date_from" type="date" class="form-control" name="filter_date_from" value="{{$date_from}}"/>
            </div>
          </div>
          <div class="filter_date_to_wrapper form-group col-md-3">
            <div class="filter_label_wrapper col-xs-2">
              <label for="filter_date_to">До:</label>
            </div>
            <div class="filter_input_wrapper col-xs-10">
              <input id="filter_date_to" type="date" class="form-control" name="filter_date_to" value="{{$date_to}}"/>
            </div>
          </div>
          <div class="filter_submit_wrapper form-group col-md-2">
            <button type="submit" class="btn btn-primary col-xs-12">Филтрирај</button>
          </div>




          @if ($errors->has('donations-filter-select'))
            <span class="help-block">
              <strong>{{ $errors->first('donations-filter-select') }}</strong>
            </span>
          @endif
    		</div>

      </form>
    </div>
  </section>

  @foreach ($hub_listings->get() as $hub_listing)

        <div id="listingbox{{$hub_listing->id}}" name="listingbox{{$hub_listing->id}}"></div>
          <!-- Default box -->
          <div class="{{($selected_filter == "active") ? "hub-my-active-listings-box" : "hub-my-past-listings-box"}}
                box listing-box listing-box-{{$hub_listing->id}} {{($hub_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
            <div class="box-header with-border listing-box-header hub-listing-box-header">

                <div class="listing-image">
                  @if ($hub_listing->image_id)
                    <img class="img-rounded" alt="{{$hub_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($hub_listing->image_id)->filename)}}" />
                  @elseif ($hub_listing->product->food_type->image_id)
                    <img class="img-rounded" alt="{{$hub_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($hub_listing->product->food_type->image_id)->filename)}}" />
                  @else
                    <img class="img-rounded" alt="{{$hub_listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$hub_listing->id}}" class="listing-title col-xs-12">
                    <div class="col-md-10 col-xs-12 hub-listing-title-panel panel">
                      <strong>{{$hub_listing->product->food_type->name}} | {{$hub_listing->product->name}}</strong>
                    </div>
                    <div class="col-md-2 col-xs-12">
                      <button type="button" id="hub-listing-details-{{$hub_listing->id}}"
                            class="btn btn-primary pull-right hub-listing-details hub-listing-details-{{$hub_listing->id}}"
                            name="button" data-toggle="modal" data-target="#hub-listing-details-popup">Детали...</button>
                    </div>

                  </div>
                  <div class="header-elements-wrapper hub-header-elements">

                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <span class="col-xs-12">Преостаната количина:</span>
                      <span class="col-xs-12" id="quantity-left-{{$hub_listing->id}}"><strong>{{$hub_listing->quantity - $hub_listing->listing_offers->where('offer_status','active')->sum('quantity')}} (од {{$hub_listing->quantity}}) {{$hub_listing->quantity_type->description}}</strong></span>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="col-xs-12 row">Прифатени:</div>

                      @switch($hub_listing->listing_offers_count)
                          @case(0)
                          <div class="col-xs-12" id="accepted-quantity-{{$hub_listing->id}}"><strong>Нема</strong></div>
                              @break

                          @default
                          <div class="col-xs-12 row">
                            <ul class="list-group">
                          @foreach ($hub_listing->listing_offers as $listing_offer)
                            @if ($listing_offer->offer_status == 'active')
                              <a href="{{url('hub/cso_accepted_listings/' . $listing_offer->id)}}" id="hub-listing-offer-button-{{$listing_offer->id}}" class=" hub-listing-offer-button hub-listing-offer-button-{{$listing_offer->id}} ">
                                <li class="list-group-item">
                                  <strong>{{$listing_offer->quantity}} {{$hub_listing->quantity_type->description}} од {{$listing_offer->cso->first_name}} {{$listing_offer->cso->last_name}} | {{$listing_offer->cso->organization->name}}</strong>
                                  <strong style="color: red;">{{($listing_offer->delivered_by_hub) ? "(За достава)" : ""}}</strong>
                              </li>
                              </a>
                            @endif
                          @endforeach
                            </ul>
                          </div>
                      @endswitch

                    </div>

                  </div>
              </div>
            </div>
            <!-- /.box-footer-->
          </div>
          <!-- /.box -->

          <div id="hidden-product-name-{{$hub_listing->id}}" class="hidden-product-name hidden">{{$hub_listing->product->name}}</div>
          <div id="hidden-quantity-{{$hub_listing->id}}" class="hidden-quantity hidden">{{$hub_listing->quantity}} {{$hub_listing->quantity_type->description}}</div>
          <div id="hidden-pickup-time-{{$hub_listing->id}}" class="hidden-pickup-time hidden">од {{Carbon::parse($hub_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($hub_listing->pickup_time_to)->format('H:i')}} часот</div>
          @if ($selected_filter == 'active')
            <div id="hidden-listed-{{$hub_listing->id}}" class="hidden-listed hidden">{{Carbon::parse($hub_listing->date_listed)->diffForHumans()}}</div>
            <div id="hidden-expires-in-{{$hub_listing->id}}" class="hidden-expires-in hidden">{{Carbon::parse($hub_listing->date_expires)->diffForHumans()}}</div>
          @else
            <div id="hidden-listed-{{$hub_listing->id}}" class="hidden-listed hidden">{{Carbon::parse($hub_listing->date_listed)->format('d-m-Y')}}</div>
            <div id="hidden-expires-in-{{$hub_listing->id}}" class="hidden-expires-in hidden">{{Carbon::parse($hub_listing->date_expires)->format('d-m-Y')}}</div>
          @endif
          <div id="hidden-food-type-{{$hub_listing->id}}" class="hidden-food-type hidden">{{$hub_listing->product->food_type->name}}</div>
          <div id="hidden-description-{{$hub_listing->id}}" class="hidden-description hidden">{{$hub_listing->description}}</div>
  @endforeach

  <!-- Modal -->
  <div id="hub-listing-details-popup" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 id="details-popup-title" class="modal-title popup-title details-popup-title"></h4>
          </div>
          <div id="listing-confirm-body" class="modal-body listing-confirm-body">
            <!-- Form content-->
            <h5 id="popup-info" class="popup-info row italic">
            </h5>
            <div id="details-popup-quantity" class="details-popup-quantity popup-element row">
              <div class="details-popup-quantity-label col-xs-6">
                <span class="pull-right popup-element-label">Количина:</span>
              </div>
              <div id="details-popup-quantity-value" class="details-popup-quantity-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="details-popup-pickup-time" class="details-popup-pickup-time popup-element row">
              <div class="details-popup-pickup-time-label col-xs-6">
                <span class="pull-right popup-element-label">Време за подигнување:</span>
              </div>
              <div id="details-popup-pickup-time-value" class="details-popup-pickup-time-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="details-popup-listed" class="details-popup-listed popup-element row">
              <div class="details-popup-listed-label col-xs-6">
                @if ($selected_filter == 'active')
                  <span class="pull-right popup-element-label">Важи од:</span>
                @else
                  <span class="pull-right popup-element-label">Важеше од:</span>
                @endif
              </div>
              <div id="details-popup-listed-value" class="details-popup-listed-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="details-popup-expires-in" class="details-popup-expires-in popup-element row">
              <div class="details-popup-expires-in-label col-xs-6">
                @if ($selected_filter == 'active')
                  <span class="pull-right popup-element-label">Достапна на платформата уште:</span>
                @else
                  <span class="pull-right popup-element-label">Истече на:</span>
                @endif
              </div>
              <div id="details-popup-expires-in-value" class="details-popup-expires-in-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="details-popup-food-type" class="details-popup-food-type popup-element row">
              <div class="details-popup-food-type-label col-xs-6">
                <span class="pull-right popup-element-label">Тип на храна:</span>
              </div>
              <div id="details-popup-food-type-value" class="details-popup-food-type-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="details-popup-description" class="details-popup-description popup-element row">
              <div class="details-popup-description-label col-xs-6">
                <span class="pull-right popup-element-label">Опис:</span>
              </div>
              <div id="details-popup-description-value" class="details-popup-description-value popup-element-value col-xs-6">
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

</section>
<!-- /.content -->

@endsection

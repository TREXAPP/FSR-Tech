@extends('layouts.master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header active-listings-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Достапни донации</span>
      @if ($active_listings_no > 0)
        <span> ({{$active_listings_no}})</span>
      @endif
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Донор</a></li>
      <li><a href="/{{Auth::user()->type()}}/my_active_listings"><i class="fa fa-cutlery"></i> Мои донации</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content active-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif
{{--
  @if ($errors->any())
      <div class="alert alert-danger">
        Донацијата не беше прифатена успешно. Корегирајте ги грешките и обидете се повторно
        <a href="javascript:document.getElementById('listingbox{{ old('listing_id') }}').scrollIntoView();">
          <button type="button" class="btn btn-default">Иди до донацијата</button>
        </a>
      </div>
  @endif --}}

  @foreach ($active_listings->get() as $active_listing)

        <div id="listingbox{{$active_listing->id}}" name="listingbox{{$active_listing->id}}"></div>
          <!-- Default box -->
          <div class="donor-my-active-listings-box box listing-box listing-box-{{$active_listing->id}} {{($active_listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
            <div class="box-header with-border listing-box-header donor-listing-box-header">

                <div class="listing-image">
                  {{-- <img src="../img/avatar5.png" /> --}}
                  @if ($active_listing->image_id)
                    <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($active_listing->image_id)->filename)}}" />
                  @elseif ($active_listing->product->food_type->image_id)
                    <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($active_listing->product->food_type->image_id)->filename)}}" />
                  @else
                    <img class="img-rounded" alt="{{$active_listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$active_listing->id}}" class="listing-title col-xs-12">
                    <div class="col-md-10 col-xs-12 donor-listing-title-panel panel">
                      <strong>{{$active_listing->product->food_type->name}} | {{$active_listing->product->name}}</strong>
                    </div>
                    <div class="col-md-2 col-xs-12">
                      <button type="button" id="donor-listing-details-{{$active_listing->id}}"
                            class="btn btn-primary pull-right donor-listing-details donor-listing-details-{{$active_listing->id}}"
                            name="button" data-toggle="modal" data-target="#donor-listing-details-popup">Детали...</button>
                    </div>

                  </div>
                  <div class="header-elements-wrapper donor-header-elements">

                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <span class="col-xs-12">Преостаната количина:</span>
                      <span class="col-xs-12" id="quantity-left-{{$active_listing->id}}"><strong>{{$active_listing->quantity - $active_listing->listing_offers->where('offer_status','active')->sum('quantity')}} (од {{$active_listing->quantity}}) {{$active_listing->quantity_type->description}}</strong></span>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="col-xs-12 row">Прифатени:</div>

                      @switch($active_listing->listing_offers_count)
                          @case(0)
                          <div class="col-xs-12" id="accepted-quantity-{{$active_listing->id}}"><strong>Нема</strong></div>
                              @break

                          @default
                          <div class="col-xs-12 row">
                            <ul class="list-group">
                          @foreach ($active_listing->listing_offers as $listing_offer)
                            @if ($listing_offer->offer_status == 'active')
                              {{-- <button type="button" id="donor-listing-offer-button-{{$listing_offer->id}}" name="donor-listing-offer-button" class="btn btn-success donor-listing-offer-button donor-listing-offer-button-{{$listing_offer->id}}"> --}}
                              <a href="{{url('donor/my_accepted_listings/' . $listing_offer->id)}}" id="donor-listing-offer-button-{{$listing_offer->id}}" class=" donor-listing-offer-button donor-listing-offer-button-{{$listing_offer->id}}">
                                <li class="list-group-item">
                                {{-- <span class="col-xs-12" id="accepted-quantity-{{$listing_offer->id}}"> --}}
                                  <strong>{{$listing_offer->quantity}} {{$active_listing->quantity_type->description}} од {{$listing_offer->cso->first_name}} {{$listing_offer->cso->last_name}} | {{$listing_offer->cso->organization->name}}</strong>
                                {{-- </span> --}}
                              </li>
                              </a>
                              {{-- </button> --}}
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

          <div id="hidden-product-name-{{$active_listing->id}}" class="hidden-product-name hidden">{{$active_listing->product->name}}</div>
          <div id="hidden-quantity-{{$active_listing->id}}" class="hidden-quantity hidden">{{$active_listing->quantity}} {{$active_listing->quantity_type->description}}</div>
          <div id="hidden-pickup-time-{{$active_listing->id}}" class="hidden-pickup-time hidden">од {{Carbon::parse($active_listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($active_listing->pickup_time_to)->format('H:i')}} часот</div>
          <div id="hidden-listed-{{$active_listing->id}}" class="hidden-listed hidden">{{Carbon::parse($active_listing->date_listed)->diffForHumans()}}</div>
          <div id="hidden-expires-in-{{$active_listing->id}}" class="hidden-expires-in hidden">{{Carbon::parse($active_listing->date_expires)->diffForHumans()}}</div>
          <div id="hidden-food-type-{{$active_listing->id}}" class="hidden-food-type hidden">{{$active_listing->product->food_type->name}}</div>
          <div id="hidden-description-{{$active_listing->id}}" class="hidden-description hidden">{{$active_listing->description}}</div>
  @endforeach

  <!-- Modal -->
  <div id="donor-listing-details-popup" class="modal fade" role="dialog">
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
                <span class="pull-right popup-element-label">Важи од:</span>
              </div>
              <div id="details-popup-listed-value" class="details-popup-listed-value popup-element-value col-xs-6">
              </div>
            </div>

            <div id="details-popup-expires-in" class="details-popup-expires-in popup-element row">
              <div class="details-popup-expires-in-label col-xs-6">
                <span class="pull-right popup-element-label">Истекува за:</span>
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

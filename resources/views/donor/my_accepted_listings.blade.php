@extends('layouts.master')


@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header accepted-listings-content-header">
    <h1><i class="fa fa-bookmark"></i>
      <span>Прифатенa донациja</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Донор</a></li>
      <li><a href="/{{Auth::user()->type()}}/my_active_listings"> Мои донации</a></li>
      <li><a href="/{{Auth::user()->type()}}/accepted_listings/{{$listing_offer->id}}"><i class="fa fa-bookmark"></i> Прифатенa донациja</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content accepted-listings-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


      <div id="listingbox{{$listing_offer->id}}" name="listingbox{{$listing_offer->id}}"></div>
      <!-- Default box -->
      <div class="box listing-box listing-box-{{$listing_offer->id}} collaped-box">
        <div class="box-header with-border listing-box-header">
            <div class="listing-image">
              {{-- <img src="../img/avatar5.png" /> --}}
              @if ($listing_offer->listing->image_id)
                <img class="img-rounded" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->listing->image_id)->filename)}}" />
              @elseif ($listing_offer->listing->product->food_type->default_image)
                <img class="img-rounded" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{url($listing_offer->listing->product->food_type->default_image)}}" />
              @else
                <img class="img-rounded" alt="{{$listing_offer->listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
              @endif

            </div>
            <div class="header-wrapper">
              <div id="listing-title-{{$listing_offer->id}}" class="listing-title col-xs-12 panel">
                <strong>
                  {{$listing_offer->listing->product->name}}
                </strong>
              </div>
              <div class="header-elements-wrapper">
                <div class="col-md-4 col-sm-4 col-xs-12 donor-accepted-header-element">
                  <span class="col-xs-12">Прифатена Количина:</span>
                  <span class="col-xs-12" id="quantity-offered-{{$listing_offer->id}}"><strong>{{$listing_offer->quantity}} {{$listing_offer->listing->quantity_type->description}}</strong></span>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 donor-accepted-header-element">
                  <span class="col-xs-12">Време за подигнување:</span>
                  <span class="col-xs-12" id="pickup-time-{{$listing_offer->id}}"><strong>од {{Carbon::parse($listing_offer->listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($listing_offer->listing->pickup_time_to)->format('H:i')}} часот</strong></span>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12 donor-accepted-header-element">
                  <span class="col-xs-12">Истекува за:</span>
                  <span class="col-xs-12" id="expires-in-{{$listing_offer->id}}"><strong>{{Carbon::parse($listing_offer->listing->date_expires)->diffForHumans()}}</strong></span>
                </div>
{{--
                <div class="col-md-5 col-sm-6 col-xs-12">
                  <span class="col-xs-12">Прифатена од:</span>
                  <span class="col-xs-12" id="cso-info-{{$listing_offer->id}}"><strong>{{$listing_offer->cso->first_name}} {{$listing_offer->cso->last_name}} | {{$listing_offer->cso->phone}}  | {{$listing_offer->cso->organization->name}}</strong></span>
                </div>
                <div class="col-md-4 col-sm-6 col-xs-12">
                  <span class="col-xs-12">Волонтер за подигнување:</span>
                  <span class="col-xs-12" id="cso-info-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer_pickup_name}}  | {{$listing_offer->volunteer_pickup_phone}}</strong></span>
                </div> --}}
              </div>
            </div>
        </div>
        <div class="listing-box-body-wrapper">
          <div class="box-body">
              <div class="panel col-xs-12 text-center">
                Волонтер за подигнување
              </div>
              <div class="col-md-4 col-sm-12 volunteer-image-wrapper ">
                @if ($listing_offer->volunteer->image_id)
                  <img class="img-rounded" alt="{{$listing_offer->volunteer->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing_offer->volunteer->image_id)->filename)}}" />
                @else
                  <img class="img-rounded" alt="{{$listing_offer->volunteer->first_name}}" src="{{url('img/avatar5.png')}}" />
                @endif
              </div>
              <hr>
              <div class="col-md-8 col-sm-12 volonteer-wrapper">
                <div class="row">
                  <div class="col-sm-6">
                    <span class="col-xs-12">Име и презиме:</span>
                    <span class="col-xs-12" id="volunteer-name-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer->first_name}} {{$listing_offer->volunteer->last_name}}</strong></span>
                  </div>
                  <div class="col-sm-6">
                    <span class="col-xs-12">Организација:</span>
                    <span class="col-xs-12" id="volunteer-organization-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer->organization->name}}</strong></span>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-sm-6">
                    <span class="col-xs-12">Телефон за контакт:</span>
                    <span class="col-xs-12" id="volunteer-phone-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer->phone}}</strong></span>
                  </div>
                  <div class="col-sm-6">
                    <span class="col-xs-12">Емаил:</span>
                    <span class="col-xs-12" id="volunteer-email-{{$listing_offer->id}}"><strong>{{$listing_offer->volunteer->email}}</strong></span>
                  </div>
                </div>
              </div>

          </div>
          <div class="box-footer text-center">
            Messages
          </div>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

</section>
<!-- /.content -->

@endsection

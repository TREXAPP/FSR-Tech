@extends('layouts.admin_master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header active-listings-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Донации</span>
      @if ($listings->count() > 0)
        <span> ({{$listings->count()}})</span>
      @endif
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Admin</a></li>
      <li><a href="/{{Auth::user()->type()}}/donor_listings"><i class="fa fa-cutlery"></i> Донации од донатори</a></li>
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
    	<form id="donations-filter-form" class="donations-filter-form" action="{{route('admin.donor_listings')}}" method="post">
    		<input type="hidden" name="post-type" value="filter" />
    		{{csrf_field()}}
    		<div class="filter-container">
    			<div class="form-group filter-select donations-filter-select col-md-4">
    				<select id="donations_filter_select" class="form-control donations-filter-select" name="donations-filter-select" required>
    					<option value="active" {{($selected_filter == "active") ? "selected" : ""}}>Активни донации</option>
    					<option value="past"  {{($selected_filter == "past") ? "selected" : ""}}>Изминати донации</option>
    				</select>
    			</div>
          <div class="form-group filter-select food-types-filter-select col-md-4">
            <select id="food_types_filter_select" class="form-control food-types-filter-select" name="food-types-filter-select" >
              <option value="">-- Тип на храна --</option>
              @foreach ($food_types as $food_type)
                <option value="{{$food_type->id}}" {{($food_type->id == $food_type_filter) ? "selected" : ""}}>{{$food_type->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group filter-select products-filter-select col-md-4">
            <select id="products_filter_select" class="form-control products-filter-select" name="products-filter-select" >
              <option value="">-- Производ --</option>
              @foreach ($products as $product)
                <option value="{{$product->id}}" {{($product->id == $product_filter) ? "selected" : ""}}>{{$product->name}}</option>
              @endforeach
            </select>
          </div>

    		</div>
        <div class="form-group filter-select organizations-filter-select col-md-4">
          <select id="organizations_filter_select" class="form-control organizations-filter-select" name="organizations-filter-select" >
            <option value="">-- Организација --</option>
            @foreach ($organizations as $organization)
              <option value="{{$organization->id}}" {{($organization->id == $organization_filter) ? "selected" : ""}}>{{$organization->name}}</option>
            @endforeach
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
      </form>
    </div>
  </section>

  @foreach ($listings->get() as $listing)

        <div id="listingbox{{$listing->id}}" name="listingbox{{$listing->id}}"></div>
          <!-- Default box -->
          <div class="{{($selected_filter == "active") ? "donor-my-active-listings-box" : "donor-my-past-listings-box"}}
                box listing-box listing-box-{{$listing->id}} {{($listing->id == old('listing_id')) ? 'box-error' : 'collapsed-box' }}">
            <div class="box-header with-border listing-box-header donor-listing-box-header">

                <div class="listing-image">
                  @if ($listing->image_id)
                    <img class="img-rounded" alt="{{$listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing->image_id)->filename)}}" />
                  @elseif ($listing->product->food_type->image_id)
                    <img class="img-rounded" alt="{{$listing->product->food_type->name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($listing->product->food_type->image_id)->filename)}}" />
                  @else
                    <img class="img-rounded" alt="{{$listing->product->food_type->name}}" src="{{url('img/food_types/food-general.jpg')}}" />
                  @endif

                </div>
                <div class="header-wrapper">
                  <div id="listing-title-{{$listing->id}}" class="listing-title col-xs-12">
                    <div class="col-md-10 col-xs-12 donor-listing-title-panel panel">
                      <strong>Донатор: {{$listing->donor->first_name}} {{$listing->donor->last_name}} | {{$listing->donor->organization->name}}</strong>
                      <br>
                      <span>{{$listing->product->food_type->name}} | {{$listing->product->name}}</span>
                    </div>
                    <div class="col-md-2 col-xs-12">
                      <button type="button" id="donor-listing-details-{{$listing->id}}"
                            class="btn btn-primary pull-right admin-listing-button donor-listing-details donor-listing-details-{{$listing->id}}"
                            name="button" data-toggle="modal" data-target="#donor-listing-details-popup">Детали...</button>

                      @if ($selected_filter == "active")
                        <a href="{{route('admin.edit_donor_listing', $listing->id)}}"
                          id="admin-listing-edit-{{$listing->id}}"
                          name="button"
                          class="btn btn-success pull-right admin-listing-button admin-listing-edit-{{$listing->id}}">Измени</a>
                      <button type="button" id="admin-donor-listing-delete-{{$listing->id}}"
                            class="btn btn-danger pull-right admin-listing-button admin-donor-listing-delete admin-donor-listing-delete-{{$listing->id}}"
                            name="button" data-toggle="modal"
                            data-target="#delete-listing-popup"
                            title="{{($listing->hub_listing_offers_count) ? 'Донацијата не може да биде избришана се додека постојат прифаќања од приматели' : ''}}"
                            {{($listing->hub_listing_offers_count) ? ' disabled' : ''}}>Избриши</button>
                      @endif
                    </div>

                  </div>
                  <div class="header-elements-wrapper donor-header-elements">

                    <div class="col-md-4 col-sm-4 col-xs-12">
                      <span class="col-xs-12">Преостаната количина:</span>
                      <span class="col-xs-12" id="quantity-left-{{$listing->id}}"><strong>{{$listing->quantity - $listing->hub_listing_offers->where('status','active')->sum('quantity')}} (од {{$listing->quantity}}) {{$listing->quantity_type->description}}</strong></span>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-12">
                      <div class="col-xs-12 row">Прифатени:</div>

                      @switch($listing->hub_listing_offers_count)
                          @case(0)
                          <div class="col-xs-12" id="accepted-quantity-{{$listing->id}}"><strong>Нема</strong></div>
                              @break

                          @default
                          <div class="col-xs-12 row">
                            <ul class="list-group">
                          @foreach ($listing->hub_listing_offers as $hub_listing_offer)
                            @if ($hub_listing_offer->status == 'active')
                              <a href="{{route('admin.hub_listing_offer', $hub_listing_offer->id)}}" id="donor-listing-offer-button-{{$hub_listing_offer->id}}" class=" donor-listing-offer-button donor-listing-offer-button-{{$hub_listing_offer->id}}">
                                <li class="list-group-item">
                                  <strong>{{$hub_listing_offer->quantity}} {{$listing->quantity_type->description}} од {{$hub_listing_offer->hub->first_name}} {{$hub_listing_offer->hub->last_name}} | {{$hub_listing_offer->hub->organization->name}}</strong>
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

          <div id="hidden-product-name-{{$listing->id}}" class="hidden-product-name hidden">{{$listing->product->name}}</div>
          <div id="hidden-quantity-{{$listing->id}}" class="hidden-quantity hidden">{{$listing->quantity}} {{$listing->quantity_type->description}}</div>
          <div id="hidden-pickup-time-{{$listing->id}}" class="hidden-pickup-time hidden">од {{Carbon::parse($listing->pickup_time_from)->format('H:i')}} до {{Carbon::parse($listing->pickup_time_to)->format('H:i')}} часот</div>
          @if ($selected_filter == 'active')

            <div id="hidden-listed-{{$listing->id}}" class="hidden-listed hidden">{{Carbon::parse($listing->date_listed)->diffForHumans()}}</div>
            <div id="hidden-expires-in-{{$listing->id}}" class="hidden-expires-in hidden">{{Carbon::parse($listing->date_expires)->diffForHumans()}}</div>
@else
  <div id="hidden-listed-{{$listing->id}}" class="hidden-listed hidden">{{Carbon::parse($listing->date_listed)->format('d-m-Y')}}</div>
  <div id="hidden-expires-in-{{$listing->id}}" class="hidden-expires-in hidden">{{Carbon::parse($listing->date_expires)->format('d-m-Y')}}</div>
        @endif
          <div id="hidden-food-type-{{$listing->id}}" class="hidden-food-type hidden">{{$listing->product->food_type->name}}</div>
          <div id="hidden-description-{{$listing->id}}" class="hidden-description hidden">{{$listing->description}}</div>
  @endforeach

  <!-- Details Modal -->
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
            <button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
          </div>
      </div>
    </div>
  </div>
</div>


  <!-- Delete Modal  -->
  <div id="delete-listing-popup" class="modal fade" role="dialog">
  	<div class="modal-dialog">

  		<!-- Modal content-->
  		<div class="modal-content">
  			<form id="delete-donor-listing-form" class="delete-donor-listing-form" action="{{ route('admin.donor_listings') }}" method="post">
          <input type="hidden" name="post-type" value="delete" />
  				{{ csrf_field() }}
  				<div class="modal-header">
  					<button type="button" class="close" data-dismiss="modal">&times;</button>
  					<h4 id="popup-title" class="modal-title popup-title">Избриши ја донацијата</h4>
  				</div>
  				<div id="delete-listing-body" class="modal-body delete-listing-body">
  					<!-- Form content-->
  					<h5 id="popup-info" class="popup-info row italic">
  						Дали сте сигурни дека сакате да ја избришите донацијата?
  					</h5>
  				</div>
  				<div class="modal-footer">
  					<input type="hidden" name="post-type" value="delete" />
  					<input type="submit" name="delete-listing-popup" class="btn btn-danger" value="Избриши" />
  					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
  				</div>
  			</form>
  		</div>
  	</div>
  </div>


</section>
<!-- /.content -->

@endsection

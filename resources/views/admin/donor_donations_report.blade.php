@extends('layouts.admin_master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header donations-report-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Извештај за донации од донатори</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/admin/home"> Admin</a></li>
      <li><a href="#"><i class="fa fa-cutlery"></i> Извештаи</a></li>
      <li><a href="{{route('admin.donor_donations_report')}}"><i class="fa fa-cutlery"></i> Донации од донатори</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content donations-report-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


  <!-- Filter -->
  <section class="filter donations-report-filter">
  	<div class="filter-wrapper row">
    	<form id="donations-report-filter-form" class="donations-report-filter-form" action="{{route('admin.donor_donations_report')}}" method="post">
    		<input type="hidden" name="post-type" value="filter" />
    		{{csrf_field()}}
    		<div class="filter-container">

          {{-- Организација на донаторот --}}
          <div class="filter_donor_organization_wrapper form-group col-md-4">
              <select id="filter_donor_organization" class="form-control" name="filter_donor_organization">
                <option value="">-- Организација на донаторот --</option>
                @foreach ($donor_organizations as $donor_organization)
                  <option value="{{$donor_organization->id}}" {{($donor_organization->id == $donor_organization_filter) ? "selected" : ""}}>{{$donor_organization->name}}</option>
                @endforeach
              </select>
          </div>

          {{-- Тип на храна --}}
          <div class="filter_food_type_wrapper form-group col-md-4">

            <select id="filter_food_type" class="form-control" name="filter_food_type">
              <option value="">-- Тип на храна --</option>
              @foreach ($food_types as $food_type)
                <option value="{{$food_type->id}}" {{($food_type->id == $food_type_filter) ? "selected" : ""}}>{{$food_type->name}}</option>
              @endforeach
            </select>
          </div>

          {{-- Производ --}}
          <div class="filter_product_wrapper form-group col-md-4">

              <select id="filter_product" class="form-control" name="filter_product">
                <option value="">-- Производ --</option>
                @foreach ($products as $product)
                  <option value="{{$product->id}}" {{($product->id == $product_filter) ? "selected" : ""}}>{{$product->name}}</option>
                @endforeach
              </select>
          </div>

          {{-- Организација на хабот --}}
          <div class="filter_hub_organization_wrapper form-group col-md-4">
              <select id="filter_hub_organization" class="form-control" name="filter_hub_organization">
                <option value="">-- Организација на хабот --</option>
                @foreach ($hub_organizations as $hub_organization)
                  <option value="{{$hub_organization->id}}" {{($hub_organization->id == $hub_organization_filter) ? "selected" : ""}}>{{$hub_organization->name}}</option>
                @endforeach
              </select>
          </div>


          {{-- od --}}
          <div class="filter_date_from_wrapper form-group col-md-3">
            <div class="filter_label_wrapper col-xs-2">
              <label for="filter_date_from">Од:</label>
            </div>
            <div class="filter_input_wrapper col-xs-10">
              <input id="filter_date_from" type="date" class="form-control" name="filter_date_from" value="{{$date_from}}"/>
            </div>
          </div>

          {{-- do --}}
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

          @if ($errors->has('donations-report-filter-select'))
            <span class="help-block">
              <strong>{{ $errors->first('donations-report-filter-select') }}</strong>
            </span>
          @endif
    		</div>

      </form>
    </div>
  </section>

<!-- donations table -->
<div id="donations_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Донации од донатори</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th colspan="7">Донации</th>
          <th colspan="4">Прифаќања</th>
        </tr>
        <tr>
          <th>Организација</th>
          <th>Корисник (донатор)</th>
          <th>Тип на храна</th>
          <th>Производ</th>
          <th>Донирана количина</th>
          <th>Неприфатена количина</th>
          <th>Прифатена количина во %</th>
          <th>Организација</th>
          <th>Корисник</th>
          <th>Прифатена количина</th>
          <th>Број на коментари</th>
        </tr>

        <?php
          $current_listing_id = 0;
          $total_portions_donated = 0;
          $total_portions_accepted = 0;
          $current_portion_size = 0;
        ?>

        @foreach ($hub_listing_offers as $hub_listing_offer)
          <tr>
            @if ($current_listing_id != $hub_listing_offer->listing_id)
              <?php
                $hub_listing_offers_number = Methods::hub_listing_offer_filtered_count($data, $hub_listing_offer->listing->id);
                $listing = $hub_listing_offer->listing;
                $listing_organization = $listing->donor->organization->name;
                $listing_user = $listing->donor->first_name . ' ' . $listing->donor->last_name;
                $food_type = $listing->product->food_type->name;
                $product = $listing->product->name;
                $donated_quantity = $listing->quantity;
                $donated_quantity_string = $listing->quantity . ' ' . $listing->quantity_type->name;

                //get the portion size for this product & lisitng
                $portion_size_row = FSR\ProductsQuantityType::where('product_id', $listing->product->id)
                                          ->where('quantity_type_id', $listing->quantity_type->id)->get();

                if($portion_size_row->count() > 0) {
                  $current_portion_size = $portion_size_row[0]->portion_size;
                }
                $total_portions_donated += $listing->quantity / $current_portion_size;

                //unaccepted quantity
                $accepted_quantity = 0;
                foreach ($listing->hub_listing_offers->where('status','active') as $current_hub_listing_offer) {
                    $accepted_quantity += $current_hub_listing_offer->quantity;
                }
                $unaccepted_quantity = $donated_quantity - $accepted_quantity;
                $unaccepted_quantity_string = $unaccepted_quantity . ' ' . $listing->quantity_type->name;

                //accepted quantity percentage
                if ($donated_quantity > 0) {
                  $accepted_quantity_percentage = $accepted_quantity / $donated_quantity * 100;
                  $accepted_quantity_percentage_string = sprintf("%.2f%%", $accepted_quantity_percentage);
                }
                else $accepted_quantity_percentage = "N/A";

                $current_listing_id = $hub_listing_offer->listing_id;
              ?>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}} >{{$listing_organization}}</td>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}}>{{$listing_user}}</td>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}}>{{$food_type}}</td>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}}>{{$product}}</td>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}}>{{$donated_quantity_string}}</td>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}}>{{$unaccepted_quantity_string}}</td>
              <td {{($hub_listing_offers_number > 1) ? 'rowspan = ' . $hub_listing_offers_number : ''}}>{{$accepted_quantity_percentage_string}}</td>
            @endif
            <?php
              $hub_listing_offer_organization = $hub_listing_offer->hub->organization->name;
              $hub_listing_offer_user = $hub_listing_offer->hub->first_name . ' ' . $hub_listing_offer->hub->last_name;
              $accepted_quantity = $hub_listing_offer->quantity;
              $accepted_quantity_string = $accepted_quantity . ' ' . $hub_listing_offer->listing->quantity_type->name;
              $comments_number = $hub_listing_offer->comments->where('status','active')->count();
              $comments_number_string = "<a target='_blank' href='../../admin/hub_listing_offers/" . $hub_listing_offer->id . "'>" . $comments_number . "</a>";

              $total_portions_accepted += $accepted_quantity / $current_portion_size;
            ?>

            <td>{{$hub_listing_offer_organization}}</td>
            <td>{{$hub_listing_offer_user}}</td>
            <td>{{$accepted_quantity_string}}</td>
            <td>{!!$comments_number_string!!}</td>

          </tr>

        @endforeach
        @foreach ($unaccepted_listings as $listing)
          <?php
            $listing_organization = $listing->donor->organization->name;
            $listing_user = $listing->donor->first_name . ' ' . $listing->donor->last_name;
            $food_type = $listing->product->food_type->name;
            $product = $listing->product->name;
            $donated_quantity = $listing->quantity;
            $donated_quantity_string = $listing->quantity . ' ' . $listing->quantity_type->name;

            //get the portion size for this product & lisitng
            $portion_size_row = FSR\ProductsQuantityType::where('product_id', $listing->product->id)
                                      ->where('quantity_type_id', $listing->quantity_type->id)->get();

            if($portion_size_row->count() > 0) {
              $current_portion_size = $portion_size_row[0]->portion_size;
            }
            $total_portions_donated += $listing->quantity / $current_portion_size;

          ?>
          <tr>
            <td>{{$listing_organization}}</td>
            <td>{{$listing_user}}</td>
            <td>{{$food_type}}</td>
            <td>{{$product}}</td>
            <td>{{$donated_quantity_string}}</td>
            <td>{{$donated_quantity_string}}</td>
            <td>N/A</td>
            <td colspan="4">Донацијата нема прифаќања</td>
          </tr>
        @endforeach
        <?php
          $total_portions_donated_rounded = round($total_portions_donated);
          $total_portions_accepted_rounded = round($total_portions_accepted);
          $acceptance_percentage = 0;
          if ($total_portions_donated > 0) {
            $acceptance_percentage = $total_portions_accepted / $total_portions_donated * 100;
            $acceptance_percentage_string = sprintf("%.2f%%", $acceptance_percentage);
          } else {
            $acceptance_percentage_string = "N/A";
          }
        ?>
          <tr>
            <th colspan="2"></th>
            <th colspan="2">Вкупно донирани порции:</th>
            <th>{{$total_portions_donated_rounded}}</th>
            <th>Процент на прифаќање</th>
            <th>{{$acceptance_percentage_string}}</th>
            <th colspan="2">Вкупно прифатени порции:</th>
            <th>{{$total_portions_accepted_rounded}}</th>
            <th></th>
          </tr>
      </table>
    </div>
  </div>
</div>

<!-- Modals here (if needed) -->


</section>
<!-- /.content -->

@endsection

@extends('layouts.admin_master')
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header product-donations-report-content-header">
    <h1><i class="fa fa-cutlery"></i>
      <span>Извештај за донации</span>
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/admin/home"> Admin</a></li>
      <li><a href="#"><i class="fa fa-cutlery"></i> Извештаи</a></li>
      <li><a href="{{route('admin.product_donations_report')}}"><i class="fa fa-cutlery"></i> Донации по производ</a></li>
    </ol>
  </section>


<!-- Main content -->
<section class="content product-donations-report-content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif


  <!-- Filter -->
  <section class="filter product-donations-report-filter">
  	<div class="filter-wrapper row">
    	<form id="product-donations-report-filter-form" class="product-donations-report-filter-form" action="{{route('admin.product_donations_report')}}" method="post">
    		<input type="hidden" name="post-type" value="filter" />
    		{{csrf_field()}}
    		<div class="filter-container">
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

          @if ($errors->has('product-donations-report-filter-select'))
            <span class="help-block">
              <strong>{{ $errors->first('product-donations-report-filter-select') }}</strong>
            </span>
          @endif
    		</div>

      </form>
    </div>
  </section>

<!-- Product Donations table -->
<div id="product_donations_table" class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">Донации по производ</div>
    <div class="panel-body">
      <table data-row-style="rowStyle" data-toggle="table" class="table-bordered">
        <tr>
          <th>Тип на храна</th>
          <th>Производ</th>
          <th>Донирана количина</th>
          <th>Донирани порции</th>
          <th>Прифатена количина</th>
          <th>Прифатени порции</th>
          <th>Процент на прифаќање</th>
        </tr>

        <!-- Donors -->
        <?php
          $current_food_type_id = 0;
          $total_portions_donated = 0;
          $total_portions_accepted = 0;
         ?>
        @foreach ($products as $product)
          <tr>

            <?php
              $products_count = $product->food_type->products->where('status','active')
                                                             ->count();


              $date_to_date = Carbon::parse($date_to)->addDays(1);
              $listings = FSR\Listing::where('date_listed', '>=', $date_from)
                                      ->where('date_listed', '<=', $date_to_date)
                                      ->where('product_id', $product->id)
                                      ->orderBy('quantity_type_id')->get();

              $quantity_donated_string = '';
              $quantity_accepted_string = '';
              $current_quantity_donated = 0;
              $current_quantity_type_id = 0;
              $current_quantity_type = '';
              $first_listing = true;
              $portions_donated = 0;
              $portions_accepted = 0;
              $current_quantity_accepted = 0;

              foreach($listings as $listing) {

                //quantity donated and accepted
                if ($listing->quantity_type_id != $current_quantity_type_id) {
                  if (!$first_listing) {
                    if ($quantity_donated_string != '') $quantity_donated_string .= ', ';
                    if ($quantity_accepted_string != '') $quantity_accepted_string .= ', ';
                    $quantity_donated_string .= $current_quantity_donated . ' ' . $current_quantity_type;
                    $quantity_accepted_string .= $current_quantity_accepted . ' ' . $current_quantity_type;
                  }
                  $current_quantity_donated = 0;
                  $current_quantity_accepted = 0;
                  $current_quantity_type_id = $listing->quantity_type_id;
                  $current_quantity_type = $listing->quantity_type->name;
                }

                //get the portion size for this product & lisitng
                $portion_size_row = FSR\ProductsQuantityType::where('product_id', $product->id)
                                          ->where('quantity_type_id', $current_quantity_type_id)->get();

                if($portion_size_row->count() > 0) {
                  $current_portion_size = $portion_size_row[0]->portion_size;
                }

                $current_quantity_donated += $listing->quantity;
                //portions donated
                $portions_donated += $listing->quantity / $current_portion_size;

                foreach($listing->listing_offers->where('offer_status','active') as $listing_offer) {
                  $current_quantity_accepted += $listing_offer->quantity;
                  //portions accepted
                  $portions_accepted += $listing_offer->quantity / $current_portion_size;
                }



                if ($first_listing) $first_listing = false;
              }
              if ($quantity_donated_string != '') $quantity_donated_string .= ', ';
              $quantity_donated_string .= $current_quantity_donated . ' ' . $current_quantity_type;

              if ($quantity_accepted_string != '') $quantity_accepted_string .= ', ';
              $quantity_accepted_string .= $current_quantity_accepted . ' ' . $current_quantity_type;

              $portions_donated = round($portions_donated);
              $total_portions_donated += $portions_donated;

              $portions_accepted = round($portions_accepted);
              $total_portions_accepted += $portions_accepted;

              $acceptance_percentage = 0;
              if ($portions_donated > 0) {
                $acceptance_percentage = $portions_accepted / $portions_donated * 100;
                $acceptance_percentage_string = sprintf("%.2f%%", $acceptance_percentage);
              } else {
                $acceptance_percentage_string = "N/A";
              }
            ?>
            @if ($current_food_type_id != $product->food_type_id)
              <?php
                $current_food_type_id = $product->food_type_id;
              ?>
              <td {{($products_count > 1) ? 'rowspan=' . $products_count : ''}}>{{$product->food_type->name}}</td>
            @endif
            <td>{{$product->name}}</td>
            <td>{{$quantity_donated_string}}</td>
            <td>{{$portions_donated}}</td>
            <td>{{$quantity_accepted_string}}</td>
            <td>{{$portions_accepted}}</td>
            <td>{{$acceptance_percentage_string}}</td>
          </tr>
        @endforeach
        <?php
        if ($total_portions_donated > 0) {
          $total_acceptance_percentage = $total_portions_accepted / $total_portions_donated * 100;
          $total_acceptance_percentage_string = sprintf("%.2f%%", $total_acceptance_percentage);
        } else {
          $total_acceptance_percentage_string = "N/A";
        }

        ?>
          <tr>
            <th></th>
            <th colspan='2' style='text-align: right;'>Вкупно донирани порции:</th>
            <th>{{$total_portions_donated}}</th>
            <th style='text-align: right;'>Вкупно прифатени порции:</th>
            <th>{{$total_portions_accepted}}</th>
            <th>{{$total_acceptance_percentage_string}}</th>
          </tr>
      </table>
    </div>
  </div>
</div>

<!-- Modals here (if needed) -->

</section>
<!-- /.content -->

@endsection

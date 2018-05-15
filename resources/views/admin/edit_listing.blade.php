@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header listing-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени донација</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/listings">
				<i class="fa fa-user-circle"></i> Донации</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/listings/edit/"{{$listing->id}}>Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content listing-content edit-listing-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-listing-box listing-box box">
	<form id="edit-listing-form" class="" action="{{ route('admin.edit_listing',$listing->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="listing_id" value="{{$listing->id}}">
            {{ csrf_field() }}
		<div class="listing-box-body-wrapper">
			<div class="box-body">



				<!-- food type -->
				<br/>
				<div class="form-group{{ $errors->has('food_type') ? ' has-error' : '' }} row">
					<label for="food_type" class="col-md-2 col-md-offset-2 control-label">Категорија на храна</label>
					<div class="col-md-6">
						<select id="food_type_select_admin" class="form-control" name="food_type" required>
							@foreach ($food_types as $food_type)
								<option value={{$food_type->id}}
									{{ (old('food_type') == $food_type->id) ? ' selected' : (($listing->product->food_type->id == $food_type->id) ? ' selected' : '')}}>{{$food_type->name}}</option>
							@endforeach
						</select>
						 @if ($errors->has('food_type'))
						<span class="help-block">
								<strong>{{ $errors->first('food_type') }}</strong>
						</span>
						@endif
					</div>
				</div>


				<!-- Product select -->
				<div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }} row">
					<label for="product_id" class="col-md-2 col-md-offset-2 control-label">Тип на производи</label>

					<div class="col-md-6">
						<select id="product_id_select_admin" class="form-control" name="product_id" required>
						@foreach ($products as $product)
							<option value={{$product->id}}{{ (old('product_id') == $product->id) ? ' selected' : (($listing->product->id == $product->id) ? ' selected' : '')}}>{{$product->name}}</option>
						@endforeach
					</select>

						@if ($errors->has('product_id'))
						<span class="help-block">
								<strong>{{ $errors->first('product_id') }}</strong>
						</span>
						@endif
					</div>
				</div>


				<!-- Description -->
				<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} row">
					<label for="description" class="col-md-2 col-md-offset-2 control-label">Опис</label>
					<div class="col-md-6">
						<textarea rows="4" form="edit-listing-form" id="description" class="form-control"
											name="description" >{{ (old('description')) ? old('description') : $listing->description }}</textarea>
						@if ($errors->has('description'))
						<span class="help-block">
								<strong>{{ $errors->first('description') }}</strong>
						</span>
						@endif
					</div>
				</div>

					<!-- Quantity -->
					<div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }} row">
						<label for="quantity" class="col-md-2 col-md-offset-2 control-label">Количина</label>

						<div class="col-md-6">
							<div class="col-xs-6" style="padding-left: 0;">
								<input type="number" id="quantity" name="quantity" class="form-control"
											min="{{$max_accepted_quantity}}" max="99999999" step="0.1"
											value="{{ (old('quantity')) ? old('quantity') : $listing->quantity }}" >
								@if ($errors->has('quantity'))
								<span class="help-block">
										<strong>{{ $errors->first('quantity') }}</strong>
								</span>
								@endif
							</div>

							<div class="col-xs-6" style="padding-right: 0;">
								<select id="quantity_type_admin" class="form-control" name="quantity_type">
									{{-- <option value="">-- Избери --</option> --}}
									@foreach ($listing->product->quantity_types as $quantity_type)
										<option value={{$quantity_type->id}}
											{{ (old('quantity_type') == $quantity_type->id) ? ' selected' : (($listing->quantity_type->id == $quantity_type->id) ? ' selected' : '')}}>{{$quantity_type->description}}</option>
									@endforeach
								</select>
								 @if ($errors->has('quantity_type'))
								<span class="help-block">
										<strong>{{ $errors->first('quantity_type') }}</strong>
								</span>
								@endif
							</div>


						</div>
					</div>

						<!-- date_listed -->
            <div class="form-group{{ $errors->has('date_listed') ? ' has-error' : '' }} row">
              <label for="date_listed" class="col-md-2 col-md-offset-2 control-label">Донацијата важи од</label>

              <div class="col-md-6">
							  <input id="date_listed" type="datetime-local" class="form-control" name="date_listed"
                      value="{{(old('date_listed')) ? old('date_listed') : Methods::convert_date_input_from_db($listing->date_listed)}}" style="text-align: center;" required >
                @if ($errors->has('date_listed'))
                <span class="help-block">
                    <strong>{{ $errors->first('date_listed') }}</strong>
                </span>
                @endif
              </div>
            </div>


						<!-- sell_by_date -->
						<div class="form-group{{ $errors->has('sell_by_date') ? ' has-error' : '' }} row">
							<label for="sell_by_date" class="col-md-2 col-md-offset-2 control-label">Употребливо до</label>

							<div class="col-md-6">
								<input id="sell_by_date" type="date" class="form-control" name="sell_by_date" max="9999-01-01"
											value="{{(old('sell_by_date')) ? old('sell_by_date') : $listing->sell_by_date}}" style="text-align: center;" required >

								@if ($errors->has('sell_by_date'))
								<span class="help-block">
										<strong>{{ $errors->first('sell_by_date') }}</strong>
								</span>
								@endif
							</div>
						</div>


						<!-- date_expires -->
            <div class="form-group{{ $errors->has('date_expires') ? ' has-error' : '' }} row">
              <label for="date_expires" class="col-md-2 col-md-offset-2 control-label">Донацијата ќе биде достапна до</label>

              <div class="col-md-6">
                <input id="date_expires" type="datetime-local" class="form-control" name="date_expires"
                      value="{{(old('date_expires')) ? old('date_expires') : Methods::convert_date_input_from_db($listing->date_expires)}}" style="text-align: center;" required >
								@if ($errors->has('date_expires'))
                <span class="help-block">
                    <strong>{{ $errors->first('date_expires') }}</strong>
                </span>
                @endif
              </div>
            </div>



							<!-- pickup_time_from -->
						<div class="form-group{{ $errors->has('pickup_time_from') ? ' has-error' : '' }} row">
							<label class="col-md-2 col-md-offset-2 control-label" for="listing-pickup_time_from">Време за подигање од:</label>
							<div class="col-md-6">
								<div class="col-xs-6"  style="padding-left: 0;">
									<input id="pickup_time_from" type="time" step="3600" name="pickup_time_from" class="form-control"
												value="{{ (old('pickup_time_from')) ? old('pickup_time_from') : $listing->pickup_time_from }}" >
								</div>
								<div class="col-xs-6" style="padding-right: 0px;">
									<span>часот</span>
								</div>
								@if ($errors->has('pickup_time_from'))
									<span class="help-block">
										<strong>{{ $errors->first('pickup_time_from') }}</strong>
									</span>
								@endif
							</div>
						</div>


							<!-- pickup_time_to -->
						<div class="form-group{{ $errors->has('pickup_time_to') ? ' has-error' : '' }} row">
							<label class="col-md-2 col-md-offset-2 control-label" for="listing-pickup_time_to">Време за подигање до:</label>
							<div class="col-md-6">
								<div class="col-xs-6"  style="padding-left: 0;">
									<input id="pickup_time_to" type="time" step="3600" name="pickup_time_to" class="form-control"
												value="{{ (old('pickup_time_to')) ? old('pickup_time_to') : $listing->pickup_time_to }}" >
								</div>
								<div class="col-xs-6" style="padding-right: 0px;">
									<span>часот</span>
								</div>
								@if ($errors->has('pickup_time_to'))
									<span class="help-block">
										<strong>{{ $errors->first('pickup_time_to') }}</strong>
									</span>
								@endif
							</div>
						</div>




			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-listing-submit" type="submit" name="edit-listing-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.listings')}}"
						id="cancel-edit-listing" name="cancel-edit-listing"
				class="btn btn-default">Откажи</a>
			</div>
		</div>
	</form>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->


</section>
<!-- /.content -->

@endsection

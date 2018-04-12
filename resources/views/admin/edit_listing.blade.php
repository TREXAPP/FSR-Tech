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
						<select id="food_type_select" class="form-control" name="food_type" required>
							<option value="">-- Избери --</option>
							@foreach ($food_types as $food_type)
								<option value={{$food_type->id}}
									{{ (old('food_type') == $food_type->id) ? ' selected' : (($product->food_type->id == $food_type->id) ? ' selected' : '')}}>{{$food_type->name}}</option>
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
						<select id="product_id_select" class="form-control" name="product_id"  {{ (!old('food_type')) ? ' disabled' : '' }} required>
								<option value="">-- Избери --</option>
						@if ($products)
						@foreach ($products as $product)
							<option value={{$product->id}}{{ (old('product_id') == $product->id) ? ' selected' : (($listing->product->id == $product->id) ? ' selected' : '')}}>{{$product->name}}</option>
						@endforeach
						@endif
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
							<input type="number" id="quantity" name="quantity" class="form-control"
										min="{{$max_accepted_quantity}}" max="99999999" step="0.1"
										value="{{ (old('quantity')) ? old('quantity') : $listing->quantity }}" >
							@if ($errors->has('quantity'))
							<span class="help-block">
									<strong>{{ $errors->first('quantity') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<!-- Quantity type -->
						<div class="row form-group{{ ($errors->has('quantity-type')) ? ' has-error' : '' }}">
							<div class="quantity-type-label col-sm-4 col-xs-12">
								<label for="quantity-type">Количина:</label>
							</div>
							<div class="quantity-type-value col-sm-8 col-xs-12">
								<select id="quantity-type" class="form-control" name="quantity-type">
									{{-- <option value="">-- Избери --</option> --}}
									@foreach ($listing->product->quantity_types as $quantity_type)
										<option value={{$quantity_type->id}}
											{{ (old('quantity-type') == $quantity_type->id) ? ' selected' : (($listing->quantity_type->id == $quantity_type->id) ? ' selected' : '')}}>{{$quantity_type->description}}</option>
									@endforeach
								</select>
								 @if ($errors->has('quantity-type'))
								<span class="help-block">
										<strong>{{ $errors->first('quantity-type') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<!-- date-listed -->
            <div class="form-group{{ $errors->has('date-listed') ? ' has-error' : '' }} row">
              <label for="date-listed" class="col-md-2 col-md-offset-2 control-label">Донацијата важи од</label>

              <div class="col-md-6">
                {{-- <input id="date-listed" type="datetime-local" class="form-control" name="date-listed" value="{{(old('date-listed')) ? old('date_listed') : $now}}" style="text-align: center;" required > --}}
                <input id="date-listed" type="datetime-local" class="form-control" name="date-listed"
                      value="{{(old('date-listed')) ? old('date-listed') : $listing->date_listed}}" style="text-align: center;" required >
                @if ($errors->has('date-listed'))
                <span class="help-block">
                    <strong>{{ $errors->first('date-listed') }}</strong>
                </span>
                @endif
              </div>
            </div>

						<!-- date-expires -->
            <div class="form-group{{ $errors->has('date-expires') ? ' has-error' : '' }} row">
              <label for="date-expires" class="col-md-2 col-md-offset-2 control-label">Донацијата важи до</label>

              <div class="col-md-6">
                {{-- <input id="date-expires" type="datetime-local" class="form-control" name="date-expires" value="{{(old('date-expires')) ? old('date_listed') : $now}}" style="text-align: center;" required > --}}
                <input id="date-expires" type="datetime-local" class="form-control" name="date-expires"
                      value="{{(old('date-expires')) ? old('date-expires') : $listing->date_expires}}" style="text-align: center;" required >
                @if ($errors->has('date-expires'))
                <span class="help-block">
                    <strong>{{ $errors->first('date-expires') }}</strong>
                </span>
                @endif
              </div>
            </div>


						<!-- sell_by_date -->
						<div class="form-group{{ $errors->has('sell_by_date') ? ' has-error' : '' }} row">
							<label for="sell_by_date" class="col-md-2 col-md-offset-2 control-label">Рок на важност на храната</label>

							<div class="col-md-6">
								<input id="sell_by_date" type="date" class="form-control" name="sell_by_date" min="{{Carbon::now()->format('Y-m-d')}}" max="9999-01-01"
											value="{{(old('sell_by_date')) ? old('sell_by_date') : ''}}" style="text-align: center;" required >
								@if ($errors->has('sell_by_date'))
								<span class="help-block">
										<strong>{{ $errors->first('sell_by_date') }}</strong>
								</span>
								@endif
							</div>
						</div>


							<!-- pickup-time-from -->
						<div class="row pickup-time-from form-group{{ ($errors->has('pickup-time-from')) ? ' has-error' : '' }}">
							<div class="listing-pickup-time-from-label col-sm-4 col-xs-12">
								<label for="listing-pickup-time-from">Време за подигање од:</label>
							</div>
							<div class="listing-address-value col-sm-8 col-xs-12">
								<div>
									<input id="pickup-time-from" type="time" step="3600" name="pickup-time-from" class="form-control"
												value="{{ (old('pickup-time-from')) ? old('pickup-time-from') : $listing->pickup_time_from }}" >
								</div>
								<div class="col-xs-6" style="padding-right: 0px;">
									<span>часот</span>
								</div>
								@if ($errors->has('pickup-time-from'))
									<span class="help-block">
										<strong>{{ $errors->first('pickup-time-from') }}</strong>
									</span>
								@endif
							</div>
						</div>


							<!-- pickup-time-to -->
						<div class="row pickup-time-to form-group{{ ($errors->has('pickup-time-to')) ? ' has-error' : '' }}">
							<div class="listing-pickup-time-to-label col-sm-4 col-xs-12">
								<label for="listing-pickup-time-to">Време за подигање до:</label>
							</div>
							<div class="listing-address-value col-sm-8 col-xs-12">
								<div>
									<input id="pickup-time-to" type="time" step="3600" name="pickup-time-to" class="form-control"
												value="{{ (old('pickup-time-to')) ? old('pickup-time-to') : $listing->pickup_time_to }}" >
								</div>
								<div class="col-xs-6" style="padding-right: 0px;">
									<span>часот</span>
								</div>
								@if ($errors->has('pickup-time-to'))
									<span class="help-block">
										<strong>{{ $errors->first('pickup-time-to') }}</strong>
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

@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header product-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени производ</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="{{route('admin.products')}}">
				<i class="fa fa-user-circle"></i> Производи</a>
		</li>
		<li>
			<a href="{{route('admin.edit_product', $product->id)}}">Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->

  <section class="content">

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

  <!-- Default box -->
  <div class="box col-md-12">

		<form id="edit-product-form" class="" action="{{ route('admin.edit_product',$product->id) }}" method="post" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="product_id" value="{{$product->id}}">

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <!-- food type -->
          <div class="form-group{{ $errors->has('food_type') ? ' has-error' : '' }} row">
            <label for="food_type" class="col-md-2 col-md-offset-2 control-label">Категорија на храна</label>
            <div class="col-md-6">
              <select id="admin_food_type_select" class="form-control" name="food_type" required>
                <option value="">-- Избери --</option>
                @foreach ($food_types as $food_type)
									<option value={{$food_type->id}}
										{{ (old('food_type') == $food_type->id) ? ' selected' : (($product->food_type_id == $food_type->id) ? ' selected' : '')}}>{{$food_type->name}}</option>
                @endforeach
              </select>
               @if ($errors->has('food_type'))
              <span class="help-block">
                  <strong>{{ $errors->first('food_type') }}</strong>
              </span>
              @endif
            </div>
          </div>


            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name"
                      value="{{ (old('name')) ? old('name') : $product->name }}" required >
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <!-- Description -->
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} row">
              <label for="description" class="col-md-2 col-md-offset-2 control-label">Опис</label>

              <div class="col-md-6">
                {{-- <textarea rows="4" form="new_listing_form" id="description" class="form-control" name="description" required >{{ old('description') }}</textarea> --}}
                <textarea rows="4" form="edit-product-form" id="description" class="form-control"
                          placeholder=""
                          name="description" >{{ (old('description')) ? old('description') : $product->description }}</textarea>
                @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <hr>

            <!-- Quantity types -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="quantity_type1" class="col-md-2 col-md-offset-2 control-label">Тип на количина</label>
              <div class="col-md-6">
                <div class="col-xs-12 admin-quantity-type-wrapper">
									<?php $count = 0; ?>
									@foreach ($product->quantity_types as $product_quantity_type)
									<?php $count++; ?>
										<div id="admin-quantity-type-entry-{{$count}}" class="admin-quantity-type-entry row">
											<div class="col-md-6 admin-quantity-type-select">
												<select class="form-control" name="quantity_type_{{$count}}" required>
													<option value="">-- Избери --</option>

													@foreach ($quantity_types as $quantity_type)

														<option value="{{$quantity_type->id}}"
															{{ (old('quantity_type') == $quantity_type->id) ? ' selected' : (($product_quantity_type->id == $quantity_type->id) ? ' selected' : '')}}>{{$quantity_type->description}}</option>

													@endforeach
												</select>
											</div>
											<div class="col-md-6 admin-quantity-type-portion-size">
												<input type="number" class="form-control" name="portion_size_{{$count}}" min="0" max="999999" step="0.0001"
															value="{{ (old('portion_size_' . $count) ? old('portion_size_' . $count) : (($product_quantity_type->pivot->portion_size) ? $product_quantity_type->pivot->portion_size : '')) }}" placeholder="Порција" required>
											</div>
											<div class="admin-quantity-type-radio">
												<label class="custom-radio-container">Дифолт
													<input type="radio" name="quantity_type_default" value="{{$count}}" required
													{{($product_quantity_type->pivot->default) ? ' checked="checked"' : ''}}>
													<span class="checkmark"></span>
												</label>
											</div>
										</div>

									@endforeach
									<input type="hidden" id="number-of-quantity-types" name="number_of_quantity_types" value="{{$count}}">
                </div>
                <div class="col-xs-12 add-new-quantity-type-wrapper">
                  <button type="button" class="btn btn-primary" id="add-new-quantity-type" name="add_new_quantity_type"><i class="fa fa-plus"></i></button>
                  <button type="button" class="btn btn-danger" id="remove-quantity-type" name="remove_quantity_type"
												{{($count > 1) ? '' : ' style=display:none;'}} ><i class="fa fa-minus"></i></button>
                </div>

              </div>

            </div>


          </div>
        </div>
      </div>

			<div class="box-footer text-center">
  			<div class="pull-right">
  				<button id="edit-product-submit" type="submit" name="edit-product-submit" class="btn btn-success" >Измени</button>
  				<a href="{{route('admin.products')}}" id="cancel-product-type" name="cancel-product-type"
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

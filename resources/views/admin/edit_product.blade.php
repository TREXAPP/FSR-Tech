@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header product-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени донор</span>
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
                <textarea rows="4" form="new_product_form" id="description" class="form-control"
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
                  <input type="hidden" id="number-of-quantity-types" name="number_of_quantity_types" value="1">
                  <div id="admin-quantity-type-entry-1" class="admin-quantity-type-entry row">
                    <div class="col-md-6 admin-quantity-type-select">
                      <select class="form-control" name="quantity_type_1" required>
                        <option value="">-- Избери --</option>
                        @foreach ($quantity_types as $quantity_type)
                          <option value="{{$quantity_type->id}}">{{$quantity_type->description}}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="col-md-6 admin-quantity-type-portion-size">
                      <input type="number" class="form-control" name="portion_size_1" min="0" max="999999" step="0.0001" value="" placeholder="Порција" required>
                    </div>
                    <div class="admin-quantity-type-radio">
                      <label class="custom-radio-container">Дифолт
                        <input type="radio" checked="checked" name="quantity_type_default" value="1" required>
                        <span class="checkmark"></span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-xs-12 add-new-quantity-type-wrapper">
                  <button type="button" class="btn btn-primary" id="add-new-quantity-type" name="add_new_quantity_type"><i class="fa fa-plus"></i></button>
                  <button type="button" class="btn btn-danger" id="remove-quantity-type" name="remove_quantity_type" style="display: none;"><i class="fa fa-minus"></i></button>
                </div>

              </div>

            </div>





            <!-- Upload image -->
            {{-- <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} row">
              <label for="image" class="col-md-2 col-md-offset-2 control-label">Слика</label>

              <div class="col-md-6">
                <input id="image" type="file" class="form-control" name="image"
                      value="{{ old('image') }}" {{ (!old('type')) ? ' disabled' : '' }} >
                @if ($errors->has('image'))
                <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
                @endif
              </div>
            </div> --}}


          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right">
            Внеси нов производ
        </button>
      </div>
    </form>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection

@extends('layouts.master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нова донација
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Донор</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_listing"><i class="fa fa-plus-circle"></i> Додади нова донација</a></li>
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
    <div class="box-header with-border">
      <p class="box-title">Во формата подолу внесете ги потребните податоци за храната која сте спремни да ја донирате.</p>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">



          <form id="new_listing_form" class="" action="{{ route('donor.new_listing') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <!-- food type -->
            <br/>
            <div class="form-group{{ $errors->has('food_type') ? ' has-error' : '' }} row">
              <label for="food_type" class="col-md-2 col-md-offset-2 control-label">Категорија на храна</label>
              <div class="col-md-6">
                <select id="food_type_select" class="form-control" name="food_type">
                  <option value="">-- Избери --</option>
                  @foreach ($food_types as $food_type)
                    <option value={{$food_type->id}}{{ (old('food_type') == $food_type->id) ? ' selected' : ''}}>{{$food_type->name}}</option>
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
                <select id="product_id_select" class="form-control" name="product_id"  {{ (!old('food_type')) ? ' disabled' : '' }}>
                    <option value="">-- Избери --</option>
                @foreach ($products as $product)
                  <option value={{$product->id}}{{ (old('product_id') == $product->id) ? ' selected' : ''}}>{{$product->name}}</option>
                @endforeach
              </select>

                @if ($errors->has('product_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('product_id') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- quantity and quantity_types -->
            <div class="form-group{{ ($errors->has('quantity') || $errors->has('quantity_type')) ? ' has-error' : '' }} row">
              <label for="quantity" class="col-md-2 col-md-offset-2 control-label">Количина</label>

              <div class="col-md-6">
                <div class="col-xs-6" style="padding-left: 0px;">
                  {{-- <input id="quantity" type="number" name="quantity" min="0" max="99999999" step="1"  class="form-control" name="quantity"  value="{{ old('quantity') }}" style="text-align: center;" required > --}}
                  <input id="quantity" type="number" name="quantity" min="0" max="99999999" step="0.1"
                        class="form-control" name="quantity"  value="{{ old('quantity') }}" style="text-align: center;" required >
                </div>
                <div class="col-xs-6"  style="padding-right: 0px;">
                  <select id="quantity_type" class="form-control" name="quantity_type">
                    @foreach ($quantity_types as $quantity_type)
                      <option value={{$quantity_type->id}}{{ (old('quantity_type') == $quantity_type->id) ? ' selected' : ''}}>
                        {{$quantity_type->description}}
                      </option>
                    @endforeach
                  </select>
                </div>
                @if ($errors->has('quantity'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </span>
                @endif
                @if ($errors->has('quantity-type'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity_type') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- Description -->
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} row">
              <label for="description" class="col-md-2 col-md-offset-2 control-label">Опис</label>

              <div class="col-md-6">
                {{-- <textarea rows="4" form="new_listing_form" id="description" class="form-control" name="description" required >{{ old('description') }}</textarea> --}}
                <textarea rows="4" form="new_listing_form" id="description" class="form-control"
                          placeholder="Подетално опишете ја состојбата и типот на храната"
                          name="description" >{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
              </div>
            </div>


{{--

            <!-- Upload image -->
            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} row">
              <label for="image" class="col-md-2 col-md-offset-2 control-label">Слика</label>

              <div class="col-md-6">
                <input id="image" type="file" class="form-control" name="image" value="{{ old('image') }}">
                @if ($errors->has('image'))
                <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
                @endif
              </div>
            </div> --}}

            <!-- sell_by_date -->
            <div class="form-group{{ $errors->has('sell_by_date') ? ' has-error' : '' }} row">
              <label for="sell_by_date" class="col-md-2 col-md-offset-2 control-label">Рок на важност на храната</label>

              <div class="col-md-6">
                <input id="sell_by_date" type="date" class="form-control" name="sell_by_date" min="2017-01-01" max="9999-01-01"
                      value="{{(old('sell_by_date')) ? old('sell_by_date') : $now}}" style="text-align: center;" required >
                @if ($errors->has('sell_by_date'))
                <span class="help-block">
                    <strong>{{ $errors->first('sell_by_date') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <hr>



            <!-- date_listed -->
            <div class="form-group{{ $errors->has('date_listed') ? ' has-error' : '' }} row">
              <label for="date_listed" class="col-md-2 col-md-offset-2 control-label">Донацијата важи од</label>

              <div class="col-md-6">
                {{-- <input id="date_listed" type="datetime-local" class="form-control" name="date_listed" value="{{(old('date_listed')) ? old('date_listed') : $now}}" style="text-align: center;" required > --}}
                <input id="date_listed" type="datetime-local" class="form-control" name="date_listed"
                      value="{{(old('date_listed')) ? old('date_listed') : $now}}" style="text-align: center;" required >
                @if ($errors->has('date_listed'))
                <span class="help-block">
                    <strong>{{ $errors->first('date_listed') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- expires_in -->
            <div class="form-group{{ $errors->has('expires_in') ? ' has-error' : '' }} row">
              <label for="expires_in" class="col-md-2 col-md-offset-2 control-label">Донацијата ќе биде активна</label>

              <div class="col-md-6">
                <div class="col-xs-6" style="padding-left: 0px;">
                  {{-- <input id="expires_in" type="number" name="expires_in" min="0" max="99999999" step="1"  class="form-control" name="expires_in" value="{{old('expires_in')}}" style="text-align: center;" required > --}}
                  <input id="expires_in" type="number" name="expires_in" min="0" max="99999999" step="1"  class="form-control"
                        name="expires_in" value="{{old('expires_in')}}" style="text-align: center;" required >
                </div>
                <div class="col-xs-6"  style="padding-right: 0px;">
                  <select id="time_type" class="form-control" name="time_type">
                      <option value="hours" {{ (old('time_type') == "hours") ? ' selected' : ''}}>часови</option>
                      <option value="days" {{ (old('time_type') == "days") ? ' selected' : ''}}>денови</option>
                      <option value="weeks" {{ (old('time_type') == "weeks") ? ' selected' : ''}}>недели</option>
                  </select>
                </div>
                @if ($errors->has('expires_in'))
                <span class="help-block col-xs-12" style="padding-left: 0px;">
                    <strong>{{ $errors->first('expires_in') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <hr>

            <!-- pickup time from -->
            <div class="form-group{{ $errors->has('pickup_time_from') ? ' has-error' : '' }} row">
              <label for="pickup_time_from" class="col-md-4 col-lg-3 col-md-offset-2 control-label">Време на подигање - Од</label>

              <div class="col-md-4 col-lg-5">
                <div class="col-xs-6" style="padding-left: 0px;">
                  {{-- <input id="pickup_time_from" type="time" class="form-control" name="pickup_time_from" value="{{old('pickup_time_from')}}"  style="text-align: center;" required > --}}
                  <input id="pickup_time_from" type="time" class="form-control" step='3600' name="pickup_time_from"
                        value="{{(old('pickup_time_from')) ? old('pickup_time_from') : Auth::user()->organization->working_hours_from}}"
                        style="text-align: center;" required >
                </div>
                <div class="col-xs-6" style="padding-right: 0px;">
                  <span>часот</span>
                </div>
                @if ($errors->has('pickup_time_from'))
                <span class="help-block col-xs-12" style="padding-left: 0px;">
                    <strong>{{ $errors->first('pickup_time_from') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- pickup time to -->
            <div class="form-group{{ $errors->has('pickup_time_to') ? ' has-error' : '' }} row">
              <label for="pickup_time_to" class="col-md-4 col-lg-3 col-md-offset-2 control-label">Време на подигање - До</label>

              <div class="col-md-4 col-lg-5">
                <div class="col-xs-6" style="padding-left: 0px;">
                  {{-- <input id="pickup_time_to" type="time" class="form-control" name="pickup_time_to" value="{{old('pickup_time_to')}}" style="text-align: center;" required > --}}
                  <input id="pickup_time_to" type="time" class="form-control" step='3600' name="pickup_time_to"
                        value="{{(old('pickup_time_to')) ? old('pickup_time_to') : Auth::user()->organization->working_hours_to}}"
                        style="text-align: center;" required >
                </div>
                <div class="col-xs-6" style="padding-right: 0px;">
                  <span>часот</span>
                </div>
                @if ($errors->has('pickup_time_to'))
                <span class="help-block col-xs-12" style="padding-left: 0px;">
                    <strong>{{ $errors->first('pickup_time_to') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <div class="form-group row">
              <div class="col-md-6 col-md-offset-6 col-lg-7 col-lg-offset-5">
                <button type="submit" class="btn btn-primary" >
                    Внеси нова донација
                </button>
              </div>
            </div>
          </form>





        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div>
    <div class="box-footer">
      Со поплнување на оваа форма се подразбира дека се согласувате со <a href="#">Правилата и прописите</a>
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection

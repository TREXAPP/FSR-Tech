@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нов производ
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_product"><i class="fa fa-plus-circle"></i> Додади нов производ</a></li>
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
      <p class="box-title">Внесете ги податоците за производот.</p>
    </div>
    <form id="new_product_form" class="" action="{{ route('admin.new_product') }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <!-- food type -->
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


            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name"
                      value="{{old('name')}}" required >
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
                          name="description" >{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
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

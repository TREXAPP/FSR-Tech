@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нова количина
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_quantity_type"><i class="fa fa-plus-circle"></i> Додади нова количина</a></li>
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
      <p class="box-title">Внесете ги податоците за новата количина</p>
    </div>
    <form id="new_food_type_form" class="" action="{{ route('admin.new_quantity_type') }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>


            <!-- Description -->
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} row">
              <label for="description" class="col-md-2 col-md-offset-2 control-label">Име</label>

              <div class="col-md-6">
                <input id="description" type="text" class="form-control" name="description"
                      value="{{old('description')}}" required >
                @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Кратенка</label>

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

            <!-- Portion size -->
            {{-- <div class="form-group{{ $errors->has('portion_size') ? ' has-error' : '' }} row">
              <label for="portion_size" class="col-md-2 col-md-offset-2 control-label">Големина на порција</label>

              <div class="col-md-6">
                <input id="portion_size" type="number" class="form-control" name="portion_size"
                      value="{{old('portion_size')}}" step=0.0001 required >
                @if ($errors->has('portion_size'))
                <span class="help-block">
                    <strong>{{ $errors->first('portion_size') }}</strong>
                </span>
                @endif
              </div>
            </div> --}}

          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right" >
            Внеси нова количина
        </button>
      </div>
    </form>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection

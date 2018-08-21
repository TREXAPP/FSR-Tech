@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нов тип на транспорт
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_transport_type"><i class="fa fa-plus-circle"></i> Додади нов тип на транспорт</a></li>
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
      <p class="box-title">Внесете ги податоците за новиот тип на транспорт</p>
    </div>
    <form id="new_food_type_form" class="" action="{{ route('admin.new_transport_type') }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>

            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name"
                      value="{{old('name')}}" >
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <!-- Количина -->
            <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }} row">
              <label for="quantity" class="col-md-2 col-md-offset-2 control-label">Количина</label>

              <div class="col-md-6">
                <input id="quantity" type="text" class="form-control" name="quantity"
                      value="{{old('quantity')}}" >
                @if ($errors->has('quantity'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </span>
                @endif
              </div>
            </div>


            <!-- Опис -->
            <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }} row">
              <label for="comment" class="col-md-2 col-md-offset-2 control-label">Опис</label>

              <div class="col-md-6">
                <input id="comment" type="text" class="form-control" name="comment"
                      value="{{old('comment')}}" >
                @if ($errors->has('comment'))
                <span class="help-block">
                    <strong>{{ $errors->first('comment') }}</strong>
                </span>
                @endif
              </div>
            </div>


          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right" >
            Внеси нов тип на транспорт
        </button>
      </div>
    </form>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection

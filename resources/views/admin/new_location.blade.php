@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нова локација
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_location"><i class="fa fa-plus-circle"></i> Додади нова локација</a></li>
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
      <p class="box-title">Внесете ги податоците за новата локација</p>
    </div>
    <form id="new_food_type_form" class="" action="{{ route('admin.new_location') }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>


            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име на локација</label>

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
                <textarea id="description" class="form-control" name="description" rows="8" cols="80">{{old('description')}}</textarea>
                @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right" >
            Внеси нова локација
        </button>
      </div>
    </form>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection

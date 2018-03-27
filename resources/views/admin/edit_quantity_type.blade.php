@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Измени количина
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/quantity_types/{{$quantity_type->id}}"><i class="fa fa-plus-circle"></i> Измени количина</a></li>
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
      <p class="box-title">Измени количина</p>
    </div>
    <form id="new_quantity_type_form" class="" action="{{ route('admin.edit_quantity_type', $quantity_type->id) }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      		<input type="hidden" name="quantity_type_id" value="{{$quantity_type->id}}">

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>

          <!-- Description -->
          <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} row">
            <label for="description" class="col-md-2 col-md-offset-2 control-label">Име</label>
            <div class="col-md-6">
              <input type="text" id="description" name="description" class="form-control"
                    value="{{ (old('description')) ? old('description') : $quantity_type->description }}" >
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
                <input type="text" id="name" name="name" class="form-control"
                      value="{{ (old('name')) ? old('name') : $quantity_type->name }}" >
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>



          </div>
        </div>
      </div>

      <div class="box-footer text-center">
  			<div class="pull-right">
  				<button id="edit-quantity-type-submit" type="submit" name="edit-quantity-type-submit" class="btn btn-success" >Измени</button>
  				<a href="{{route('admin.quantity_types')}}" id="cancel-edit-quantity-type" name="cancel-edit-quantity-type"
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

@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Измени тип на транспорт
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/transport_types/{{$transport_type->id}}"><i class="fa fa-plus-circle"></i> Измени тип на транспорт</a></li>
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
      <p class="box-title">Измени тип на транспорт</p>
    </div>
    <form id="new_transport_type_form" class="" action="{{ route('admin.edit_transport_type', $transport_type->id) }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      		<input type="hidden" name="transport_type_id" value="{{$transport_type->id}}">

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>

            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име</label>

              <div class="col-md-6">
                <input type="text" id="name" name="name" class="form-control"
                      value="{{ (old('name')) ? old('name') : $transport_type->name }}" >
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- Quantity -->
            <div class="form-group{{ $errors->has('quantity') ? ' has-error' : '' }} row">
              <label for="quantity" class="col-md-2 col-md-offset-2 control-label">Количина</label>

              <div class="col-md-6">
                <input type="text" id="quantity" name="quantity" class="form-control"
                      value="{{ (old('quantity')) ? old('quantity') : $transport_type->quantity }}" >
                @if ($errors->has('quantity'))
                <span class="help-block">
                    <strong>{{ $errors->first('quantity') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- Comment -->
            <div class="form-group{{ $errors->has('comment') ? ' has-error' : '' }} row">
              <label for="comment" class="col-md-2 col-md-offset-2 control-label">Опис</label>

              <div class="col-md-6">
                <input type="text" id="comment" name="comment" class="form-control"
                      value="{{ (old('comment')) ? old('comment') : $transport_type->comment }}" >
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

      <div class="box-footer text-center">
  			<div class="pull-right">
  				<button id="edit-transport-type-submit" type="submit" name="edit-transport-type-submit" class="btn btn-success" >Измени</button>
  				<a href="{{route('admin.transport_types')}}" id="cancel-edit-transport-type" name="cancel-edit-transport-type"
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

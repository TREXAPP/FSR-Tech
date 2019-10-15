@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Измени локација
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/locations/{{$location->id}}"><i class="fa fa-plus-circle"></i> Измени локација</a></li>
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
      <p class="box-title">Измени локација</p>
    </div>
    <form id="new_location_form" class="" action="{{ route('admin.edit_location', $location->id) }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}
      		<input type="hidden" name="location_id" value="{{$location->id}}">

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>


            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име на локација</label>

              <div class="col-md-6">
                <input type="text" id="name" name="name" class="form-control"
                      value="{{ (old('name')) ? old('name') : $location->name }}" >
                @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- Region -->
            <div class="row form-group{{$errors->has('region') ? ' has-error' : '' }}">
              <div class="col-md-2 col-md-offset-2 control-label">
                <label for="new-region">Регион:</label>
              </div>
              <div class="new-region-value col-md-6">
                <select class="form-control" name="region" required>
                  <option value="">-- Избери --</option>
                  @foreach ($regions as $region)
                  <option value="{{$region->id}}" 
                  {{ (old('region') == $region->id) ? ' selected' : (($location->region_id == $region->id) ? ' selected' : '')}}>{{$region->name}}</option>
                  @endforeach
                </select>
              @if ($errors->has('region'))
                <span class="help-block">
                  <strong>{{ $errors->first('region') }}</strong>
                </span>
              @endif
              </div>
            </div>

            <!-- Description -->
            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} row">
              <label for="description" class="col-md-2 col-md-offset-2 control-label">Опис</label>
              <div class="col-md-6">
                <textarea id="description" class="form-control" name="description"
                      rows="8" cols="80">{{ (old('description')) ? old('description') : $location->description }}</textarea>
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

      <div class="box-footer text-center">
  			<div class="pull-right">
  				<button id="edit-location-submit" type="submit" name="edit-location-submit" class="btn btn-success" >Измени</button>
  				<a href="{{route('admin.locations')}}" id="cancel-edit-location" name="cancel-edit-location"
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

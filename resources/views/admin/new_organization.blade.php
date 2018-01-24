@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нова организација
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_organization"><i class="fa fa-plus-circle"></i> Додади нова организација</a></li>
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
      <p class="box-title">Внесете ги податоците за организацијата.</p>
    </div>
    <form id="new_organization_form" class="" action="{{ route('admin.new_organization') }}" method="post" enctype="multipart/form-data">
      {{ csrf_field() }}

    <div class="box-body">
      <div class="row">
        <div class="col-xs-12 ">

          <br/>
            <!-- organization type -->
            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }} row">
              <label for="type" class="col-md-2 col-md-offset-2 control-label">Тип на организација</label>
              <div class="col-md-6">
                <select id="organization_type_select" class="form-control" name="type">
                  <option value="">@lang('register.choose')</option>
                  <option value="donor"{{ (old('type') == 'donor') ? ' selected' : ''}}>@lang('register.donor')</option>
                  <option value="cso"{{ (old('type') == 'cso') ? ' selected' : ''}}>@lang('register.cso')</option>
                </select> @if ($errors->has('type'))
                <span class="help-block">
                    <strong>{{ $errors->first('type') }}</strong>
                </span> @endif
              </div>
            </div>


            <!-- Name -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} row">
              <label for="name" class="col-md-2 col-md-offset-2 control-label">Име</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name"
                      value="{{old('name')}}" required {{ (!old('type')) ? ' disabled' : '' }}>
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
                <textarea rows="4" form="new_organization_form" id="description" class="form-control"
                          placeholder="" {{ (!old('type')) ? ' disabled' : '' }}
                          name="description" >{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- working_hours_from -->
            <div class="form-group{{ $errors->has('working_hours_from') ? ' has-error' : '' }} row">
              <label for="working_hours_from" class="col-md-4 col-lg-3 col-md-offset-2 control-label">Работно време - Од</label>

              <div class="col-md-4 col-lg-5">
                <div class="col-xs-6" style="padding-left: 0px;">
                  <input id="working_hours_from" type="time" class="form-control" step='3600' name="working_hours_from"
                        value="{{old('working_hours_from')}}"
                        required {{ (!old('type')) ? ' disabled' : '' }} >
                </div>
                <div class="col-xs-6" style="padding-right: 0px;">
                  <span>часот</span>
                </div>
                @if ($errors->has('working_hours_from'))
                <span class="help-block col-xs-12" style="padding-left: 0px;">
                    <strong>{{ $errors->first('working_hours_from') }}</strong>
                </span>
                @endif
              </div>
            </div>

            <!-- working_hours_to -->
            <div class="form-group{{ $errors->has('working_hours_to') ? ' has-error' : '' }} row">
              <label for="working_hours_to" class="col-md-4 col-lg-3 col-md-offset-2 control-label">Работно време - Од</label>

              <div class="col-md-4 col-lg-5">
                <div class="col-xs-6" style="padding-left: 0px;">
                  <input id="working_hours_to" type="time" class="form-control" step='3600' name="working_hours_to"
                        value="{{old('working_hours_to')}}"
                        required {{ (!old('type')) ? ' disabled' : '' }} >
                </div>
                <div class="col-xs-6" style="padding-right: 0px;">
                  <span>часот</span>
                </div>
                @if ($errors->has('working_hours_to'))
                <span class="help-block col-xs-12" style="padding-left: 0px;">
                    <strong>{{ $errors->first('working_hours_to') }}</strong>
                </span>
                @endif
              </div>
            </div>




            <!-- Upload image -->
            <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }} row">
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
            </div>
          </div>
        </div>
      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-primary pull-right" {{ (!old('type')) ? ' disabled' : '' }} >
            Внеси нова организација
        </button>
      </div>
    </form>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection
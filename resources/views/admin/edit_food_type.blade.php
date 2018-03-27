@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header food-type-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени категорија на храна</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/food_types">
				<i class="fa fa-user-circle"></i> Категории</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/food-types/"{{$food_type->id}}>Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content food-type-content edit-food-type-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-food-type-box food-type-box box">

	<form id="edit-food-type-form" class="" action="{{ route('admin.edit_food_type',$food_type->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="food_type_id" value="{{$food_type->id}}">
            {{ csrf_field() }}
		<div class="food-type-box-body-wrapper">
			<div class="box-body">
				<div id="food-type-image" class="col-md-4 col-xs-12 food-type-image edit-layout-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$food_type->name}}" src="{{Methods::getFileUrl(FSR\File::find($food_type->image_id)->filename)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('food-type-image')) ? ' has-error' : '' }}">
							<label for="food-type-image">Измени слика:</label>
              <input id="food-type-image" type="file" class="form-control" name="food-type-image" value="{{ old('food-type-image') }}">
							@if ($errors->has('food-type-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('food-type-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="food-type-info" class="col-md-8 col-xs-12 food-type-info">

					<!-- name -->
					<div class="row form-group{{ ($errors->has('food-type-name')) ? ' has-error' : '' }}">
						<div class="food-type-name-label col-sm-4 col-xs-12">
							<label for="food-type-name">Име:</label>
						</div>
						<div class="food-type-name-value col-sm-8 col-xs-12">
							<input type="text" name="food-type-name" class="form-control" value="{{ (old('food-type-name')) ? old('food-type-name') : $food_type->name }}" >
							@if ($errors->has('food-type-name'))
								<span class="help-block">
									<strong>{{ $errors->first('food-type-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Description -->
					<div class="row  form-group{{ ($errors->has('food-type-description')) ? ' has-error' : '' }}">
						<div class="food-type-description-label col-sm-4 col-xs-12">
							<label for="food-type-description">Опис:</label>
						</div>
						<div class="food-type-description-value col-sm-8 col-xs-12">
							<textarea id="food-type-description" class="form-control" name="food-type-description"
										rows="8" cols="80">{{ (old('food-type-description')) ? old('food-type-description') : $food_type->description }}</textarea>
							@if ($errors->has('food-type-description'))
								<span class="help-block">
									<strong>{{ $errors->first('food-type-description') }}</strong>
								</span>
							@endif
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-food-type-submit" type="submit" name="edit-food-type-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.food_types')}}" id="cancel-edit-food-type" name="cancel-edit-food-type"
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

@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени подигнувач</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/volunteers">
				<i class="fa fa-user-circle"></i> Подигнувачи</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/volunteers/"{{$volunteer->id}}>Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content volunteer-content edit-volunteer-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-volunteer-box volunteer-box box">
		<!--
		<div class="box-header with-border">

			<div id="edit-volunteer-title" class="edit-volunteer-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="edit-volunteer-form" class="" action="{{ route('admin.edit_volunteer',$volunteer->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="volunteer_id" value="{{$volunteer->id}}">
            {{ csrf_field() }}
		<div class="volunteer-box-body-wrapper">
			<div class="box-body">
				<div id="volunteer-image" class="col-md-4 col-xs-12 volunteer-image edit-layout-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{Methods::get_volunteer_image_url($volunteer)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('volunteer-image')) ? ' has-error' : '' }}">
							<label for="volunteer-image">Измени слика:</label>
              <input id="volunteer-image" type="file" class="form-control" name="volunteer-image" value="{{ old('volunteer-image') }}">
							@if ($errors->has('volunteer-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('volunteer-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="volunteer-info" class="col-md-8 col-xs-12 volunteer-info">

					<!-- First name -->
					<div class="row form-group{{ ($errors->has('volunteer-first-name')) ? ' has-error' : '' }}">
						<div class="volunteer-first-name-label col-sm-4 col-xs-12">
							<label for="volunteer-first-name">Име:</label>
						</div>
						<div class="volunteer-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-first-name" class="form-control" value="{{ (old('volunteer-first-name')) ? old('volunteer-first-name') : $volunteer->first_name }}" >
							@if ($errors->has('volunteer-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('volunteer-last-name')) ? ' has-error' : '' }}">
						<div class="volunteer-last-name-label col-sm-4 col-xs-12">
							<label for="volunteer-last-name">Презиме:</label>
						</div>
						<div class="volunteer-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-last-name" class="form-control" value="{{ (old('volunteer-last-name')) ? old('volunteer-last-name') : $volunteer->last_name }}" required>
							@if ($errors->has('volunteer-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>


					<div class="row form-group{{ ($errors->has('volunteer-email')) ? ' has-error' : '' }}">
						<div class="volunteer-email-label col-sm-4 col-xs-12">
							<label for="volunteer-email">Емаил:</label>
						</div>
						<div class="volunteer-email-value col-sm-8 col-xs-12">
							<input type="email" name="volunteer-email" class="form-control" value="{{ (old('volunteer-email')) ? old('volunteer-email') : $volunteer->email }}" required>
							@if ($errors->has('volunteer-email'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('volunteer-phone')) ? ' has-error' : '' }}">
						<div class="volunteer-phone-label col-sm-4 col-xs-12">
							<label for="volunteer-phone">Телефон:</label>
						</div>
						<div class="volunteer-phone-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-phone" class="form-control" value="{{ (old('volunteer-phone')) ? old('volunteer-phone') : $volunteer->phone }}" required>
							@if ($errors->has('volunteer-phone'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-phone') }}</strong>
								</span>
							@endif
						</div>
					</div>


				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-volunteer-submit" type="submit" name="edit-volunteer-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.volunteers')}}" id="cancel-edit-volunteer" name="cancel-edit-volunteer"
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

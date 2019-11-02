@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header active-listings-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени кориснички профил</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Примател</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/profile">
				<i class="fa fa-user-circle"></i> Кориснички профил</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/edit_profile">Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content active-listings-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="profile-box box">
		<div class="box-header with-border">

			<div id="profile-title" class="profile-title col-xs-12">
				<span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span>
			</div>

		</div>
	<form id="edit-profile-form" class="" action="{{ route('cso.edit_profile') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="profile-image" class="col-md-4 col-xs-12 profile-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$user->first_name}}" src="{{Methods::get_user_image_url(Auth::user())}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('profile-image')) ? ' has-error' : '' }}">
							<label for="profile-image">Промени слика:</label>
              <input id="profile-image" type="file" class="form-control" name="profile-image" value="{{ old('profile-image') }}">
							@if ($errors->has('profile-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('profile-image') }}</strong>
						 </span>
		 				 @endif
					</div>


				</div>
				<div id="profile-info" class="col-md-8 col-xs-12 profile-info">

					<div class="row form-group{{ ($errors->has('profile-first-name')) ? ' has-error' : '' }}">
						<div class="profile-first-name-label col-sm-4 col-xs-12">
							<label for="profile-first-name">Име:</label>
						</div>
						<div class="profile-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="profile-first-name" class="form-control" value="{{ (old('profile-first-name')) ? old('profile-first-name') : $user->first_name }}" required>
							@if ($errors->has('profile-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('profile-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row  form-group{{ ($errors->has('profile-last-name')) ? ' has-error' : '' }}">
						<div class="profile-last-name-label col-sm-4 col-xs-12">
							<label for="profile-last-name">Презиме:</label>
						</div>
						<div class="profile-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="profile-last-name" class="form-control" value="{{ (old('profile-last-name')) ? old('profile-last-name') : $user->last_name }}" required>
							@if ($errors->has('profile-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('profile-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group">
						<div class="profile-email-label col-sm-4 col-xs-12">
							<span class=""><strong>Емаил:</strong></span>
						</div>
						<div class="profile-email-value col-sm-8 col-xs-12">
							<input type="text" name="profile-email" class="form-control" value="{{$user->email}}" disabled>
						</div>
					</div>

					<div class="row form-group">
						<div class="profile-organization-label col-sm-4 col-xs-12">
							<span class=""><strong>Организација:</strong></span>
						</div>
						<div class="profile-organization-value col-sm-8 col-xs-12">
							<input type="text" name="profile-organization" class="form-control" value="{{$user->organization->name}}" disabled>
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('profile-address')) ? ' has-error' : '' }}">
						<div class="profile-address-label col-sm-4 col-xs-12">
							<label for="profile-address">Адреса:</label>
						</div>
						<div class="profile-address-value col-sm-8 col-xs-12">
							<input type="text" name="profile-address" class="form-control" value="{{ (old('profile-address')) ? old('profile-address') : $user->address }}" required>
							@if ($errors->has('profile-address'))
								<span class="help-block">
									<strong>{{ $errors->first('profile-address') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('profile-phone')) ? ' has-error' : '' }}">
						<div class="profile-phone-label col-sm-4 col-xs-12">
							<label for="profile-phone">Телефон:</label>
						</div>
						<div class="profile-phone-value col-sm-8 col-xs-12">
							<input type="text" name="profile-phone" class="form-control" value="{{ (old('profile-phone')) ? old('profile-phone') : $user->phone }}" required>
							@if ($errors->has('profile-phone'))
								<span class="help-block">
									<strong>{{ $errors->first('profile-phone') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('profile-location')) ? ' has-error' : '' }}">
						<div class="profile-location-label col-sm-4 col-xs-12">
							<label for="profile-location">Локација:</label>
						</div>
						<div class="profile-location-value col-sm-8 col-xs-12">
							<select class="form-control" name="profile-location">
								@foreach ($locations as $location)
									<option value="{{$location->id}}" {{(Auth::user()->location_id == $location->id) ? " selected" : ""}}>{{$location->name}}</option>
								@endforeach
							</select>
							@if ($errors->has('profile-location'))
								<span class="help-block">
									<strong>{{ $errors->first('profile-location') }}</strong>
								</span>
							@endif
						</div>
					</div>

				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<a href="{{url('cso/change_password')}}" id="change-password-button" name="change-password-button"
				 class="btn btn-success change-password-button">Промени лозинка</a>
				<button id="edit-profile-submit" type="submit" name="edit-profile-submit" class="btn btn-primary" >Зачувај ги измените</button>
				<a href="{{route('cso.profile')}}" id="cancel-edit-profile" name="cancel-edit-profile"
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

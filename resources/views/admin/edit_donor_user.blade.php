@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени донор</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="{{route('admin.donor_users')}}">
				<i class="fa fa-user-circle"></i> Донори</a>
		</li>
		<li>
			<a href="{{route('admin.edit_donor_user', $donor->id)}}">Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content donor-content edit-donor-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-donor-box donor-box box">
		<!--
		<div class="box-header with-border">

			<div id="edit-volunteer-title" class="edit-volunteer-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="edit-donor-form" class="" action="{{ route('admin.edit_donor_user',$donor->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="donor_id" value="{{$donor->id}}">
            {{ csrf_field() }}
		<div class="donor-box-body-wrapper">
			<div class="box-body">
				<div id="donor-image" class="col-md-4 col-xs-12 donor-image edit-layout-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$donor->first_name}}" src="{{Methods::get_donor_image_url($donor)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('donor-image')) ? ' has-error' : '' }}">
							<label for="donor-image">Измени слика:</label>
              <input id="donor-image" type="file" class="form-control" name="donor-image" value="{{ old('donor-image') }}">
							@if ($errors->has('donor-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('donor-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="donor-info" class="col-md-8 col-xs-12 donor-info">

					<!-- Email -->
					<div class="row form-group{{ ($errors->has('donor-email')) ? ' has-error' : '' }}">
						<div class="donor-email-label col-sm-4 col-xs-12">
							<label for="donor-email">Емаил:</label>
						</div>
						<div class="donor-email-value col-sm-8 col-xs-12">
							<input type="email" name="donor-email" class="form-control" value="{{ (old('donor-email')) ? old('donor-email') : $donor->email }}" disabled>
							@if ($errors->has('donor-email'))
								<span class="help-block">
									<strong>{{ $errors->first('donor-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Organization -->
					<div class="row form-group{{ ($errors->has('donor-organization')) ? ' has-error' : '' }}">
						<div class="donor-organization-type-label col-sm-4 col-xs-12">
							<label for="donor-organization">Организација:</label>
						</div>
						<div class="donor-organization-value col-sm-8 col-xs-12">
							<select id="donor-organization" class="form-control" name="donor-organization">
								<option value="">-- Избери --</option>

								@foreach ($organizations as $organization)
									<option value={{$organization->id}}
										{{ (old('donor-organization') == $organization->id) ? ' selected' : (($donor->organization_id == $organization->id) ? ' selected' : '')}}>{{$organization->name}}</option>
								@endforeach
							</select>
							 @if ($errors->has('donor-organization'))
							<span class="help-block">
									<strong>{{ $errors->first('donor-organization') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<!-- Location -->
					<div class="row form-group{{ ($errors->has('donor-location')) ? ' has-error' : '' }}">
						<div class="donor-location-type-label col-sm-4 col-xs-12">
							<label for="donor-location">Локација:</label>
						</div>
						<div class="donor-location-value col-sm-8 col-xs-12">
							<select id="donor-location" class="form-control" name="donor-location">
								<option value="">-- Избери --</option>
								@foreach ($locations as $location)
									<option value={{$location->id}}
										{{ (old('donor-location') == $location->id) ? ' selected' : (($donor->location_id == $location->id) ? ' selected' : '')}}>{{$location->name}}</option>
								@endforeach
							</select>
							 @if ($errors->has('donor-location'))
							<span class="help-block">
									<strong>{{ $errors->first('donor-location') }}</strong>
							</span>
							@endif
						</div>
					</div>


					<!-- First name -->
					<div class="row form-group{{ ($errors->has('donor-first-name')) ? ' has-error' : '' }}">
						<div class="donor-first-name-label col-sm-4 col-xs-12">
							<label for="donor-first-name">Име:</label>
						</div>
						<div class="donor-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="donor-first-name" class="form-control" value="{{ (old('donor-first-name')) ? old('donor-first-name') : $donor->first_name }}" >
							@if ($errors->has('donor-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('donor-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('donor-last-name')) ? ' has-error' : '' }}">
						<div class="donor-last-name-label col-sm-4 col-xs-12">
							<label for="donor-last-name">Презиме:</label>
						</div>
						<div class="donor-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="donor-last-name" class="form-control" value="{{ (old('donor-last-name')) ? old('donor-last-name') : $donor->last_name }}" required>
							@if ($errors->has('donor-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('donor-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Address -->
					<div class="row form-group{{ ($errors->has('donor-address')) ? ' has-error' : '' }}">
						<div class="donor-address-label col-sm-4 col-xs-12">
							<label for="donor-address">Адреса:</label>
						</div>
						<div class="donor-address-value col-sm-8 col-xs-12">
							<input type="address" name="donor-address" class="form-control"
										value="{{ (old('donor-address')) ? old('donor-address') : $donor->address }}">
							@if ($errors->has('donor-address'))
								<span class="help-block">
									<strong>{{ $errors->first('donor-address') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('donor-phone')) ? ' has-error' : '' }}">
						<div class="donor-phone-label col-sm-4 col-xs-12">
							<label for="donor-phone">Телефон:</label>
						</div>
						<div class="donor-phone-value col-sm-8 col-xs-12">
							<input type="text" name="donor-phone" class="form-control" value="{{ (old('donor-phone')) ? old('donor-phone') : $donor->phone }}" required>
							@if ($errors->has('donor-phone'))
								<span class="help-block">
									<strong>{{ $errors->first('donor-phone') }}</strong>
								</span>
							@endif
						</div>
					</div>


				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-donor-submit" type="submit" name="edit-donor-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.donor_users')}}" id="cancel-edit-donor" name="cancel-edit-donor"
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

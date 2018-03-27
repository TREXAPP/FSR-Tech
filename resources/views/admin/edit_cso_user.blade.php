@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени примател</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="{{route('admin.cso_users')}}">
				<i class="fa fa-user-circle"></i> Приматели</a>
		</li>
		<li>
			<a href="{{route('admin.edit_cso_user', $cso->id)}}">Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content cso-content edit-cso-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-cso-box cso-box box">
		<!--
		<div class="box-header with-border">

			<div id="edit-volunteer-title" class="edit-volunteer-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="edit-cso-form" class="" action="{{ route('admin.edit_cso_user',$cso->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="cso_id" value="{{$cso->id}}">
            {{ csrf_field() }}
		<div class="cso-box-body-wrapper">
			<div class="box-body">
				<div id="cso-image" class="col-md-4 col-xs-12 cso-image edit-layout-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$cso->first_name}}" src="{{Methods::get_cso_image_url($cso)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('cso-image')) ? ' has-error' : '' }}">
							<label for="cso-image">Измени слика:</label>
              <input id="cso-image" type="file" class="form-control" name="cso-image" value="{{ old('cso-image') }}">
							@if ($errors->has('cso-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('cso-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="cso-info" class="col-md-8 col-xs-12 cso-info">

					<!-- Email -->
					<div class="row form-group{{ ($errors->has('cso-email')) ? ' has-error' : '' }}">
						<div class="cso-email-label col-sm-4 col-xs-12">
							<label for="cso-email">Емаил:</label>
						</div>
						<div class="cso-email-value col-sm-8 col-xs-12">
							<input type="email" name="cso-email" class="form-control" value="{{ (old('cso-email')) ? old('cso-email') : $cso->email }}" disabled>
							@if ($errors->has('cso-email'))
								<span class="help-block">
									<strong>{{ $errors->first('cso-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Organization -->
					<div class="row form-group{{ ($errors->has('cso-organization')) ? ' has-error' : '' }}">
						<div class="cso-organization-type-label col-sm-4 col-xs-12">
							<label for="cso-organization">Организација:</label>
						</div>
						<div class="cso-organization-value col-sm-8 col-xs-12">
							<select id="cso-organization" class="form-control" name="cso-organization">
								<option value="">-- Избери --</option>

								@foreach ($organizations as $organization)
									<option value={{$organization->id}}
										{{ (old('cso-organization') == $organization->id) ? ' selected' : (($cso->organization_id == $organization->id) ? ' selected' : '')}}>{{$organization->name}}</option>
								@endforeach
							</select>
							 @if ($errors->has('cso-organization'))
							<span class="help-block">
									<strong>{{ $errors->first('cso-organization') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<!-- Location -->
					<div class="row form-group{{ ($errors->has('cso-location')) ? ' has-error' : '' }}">
						<div class="cso-location-type-label col-sm-4 col-xs-12">
							<label for="cso-location">Локација:</label>
						</div>
						<div class="cso-location-value col-sm-8 col-xs-12">
							<select id="cso-location" class="form-control" name="cso-location">
								<option value="">-- Избери --</option>
								@foreach ($locations as $location)
									<option value={{$location->id}}
										{{ (old('cso-location') == $location->id) ? ' selected' : (($cso->location_id == $location->id) ? ' selected' : '')}}>{{$location->name}}</option>
								@endforeach
							</select>
							 @if ($errors->has('cso-location'))
							<span class="help-block">
									<strong>{{ $errors->first('cso-location') }}</strong>
							</span>
							@endif
						</div>
					</div>


					<!-- First name -->
					<div class="row form-group{{ ($errors->has('cso-first-name')) ? ' has-error' : '' }}">
						<div class="cso-first-name-label col-sm-4 col-xs-12">
							<label for="cso-first-name">Име:</label>
						</div>
						<div class="cso-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="cso-first-name" class="form-control" value="{{ (old('cso-first-name')) ? old('cso-first-name') : $cso->first_name }}" >
							@if ($errors->has('cso-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('cso-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('cso-last-name')) ? ' has-error' : '' }}">
						<div class="cso-last-name-label col-sm-4 col-xs-12">
							<label for="cso-last-name">Презиме:</label>
						</div>
						<div class="cso-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="cso-last-name" class="form-control" value="{{ (old('cso-last-name')) ? old('cso-last-name') : $cso->last_name }}" required>
							@if ($errors->has('cso-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('cso-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Address -->
					<div class="row form-group{{ ($errors->has('cso-address')) ? ' has-error' : '' }}">
						<div class="cso-address-label col-sm-4 col-xs-12">
							<label for="cso-address">Адреса:</label>
						</div>
						<div class="cso-address-value col-sm-8 col-xs-12">
							<input type="address" name="cso-address" class="form-control"
										value="{{ (old('cso-address')) ? old('cso-address') : $cso->address }}">
							@if ($errors->has('cso-address'))
								<span class="help-block">
									<strong>{{ $errors->first('cso-address') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('cso-phone')) ? ' has-error' : '' }}">
						<div class="cso-phone-label col-sm-4 col-xs-12">
							<label for="cso-phone">Телефон:</label>
						</div>
						<div class="cso-phone-value col-sm-8 col-xs-12">
							<input type="text" name="cso-phone" class="form-control" value="{{ (old('cso-phone')) ? old('cso-phone') : $cso->phone }}" required>
							@if ($errors->has('cso-phone'))
								<span class="help-block">
									<strong>{{ $errors->first('cso-phone') }}</strong>
								</span>
							@endif
						</div>
					</div>


				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-cso-submit" type="submit" name="edit-cso-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.cso_users')}}" id="cancel-edit-cso" name="cancel-edit-cso"
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

@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени хаб</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="{{route('admin.hub_users')}}">
				<i class="fa fa-user-circle"></i> Хабови</a>
		</li>
		<li>
			<a href="{{route('admin.edit_hub_user', $hub->id)}}">Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content hub-content edit-hub-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-hub-box hub-box box">
		<!--
		<div class="box-header with-border">

			<div id="edit-volunteer-title" class="edit-volunteer-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="edit-hub-form" class="" action="{{ route('admin.edit_hub_user',$hub->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="hub_id" value="{{$hub->id}}">
            {{ csrf_field() }}
		<div class="hub-box-body-wrapper">
			<div class="box-body">
				<div id="hub-image" class="col-md-4 col-xs-12 hub-image edit-layout-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$hub->first_name}}" src="{{Methods::get_hub_image_url($hub)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('hub-image')) ? ' has-error' : '' }}">
							<label for="hub-image">Измени слика:</label>
              <input id="hub-image" type="file" class="form-control" name="hub-image" value="{{ old('hub-image') }}">
							@if ($errors->has('hub-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('hub-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="hub-info" class="col-md-8 col-xs-12 hub-info">

					<!-- Email -->
					<div class="row form-group{{ ($errors->has('hub-email')) ? ' has-error' : '' }}">
						<div class="hub-email-label col-sm-4 col-xs-12">
							<label for="hub-email">Емаил:</label>
						</div>
						<div class="hub-email-value col-sm-8 col-xs-12">
							<input type="email" name="hub-email" class="form-control" value="{{ (old('hub-email')) ? old('hub-email') : $hub->email }}" disabled>
							@if ($errors->has('hub-email'))
								<span class="help-block">
									<strong>{{ $errors->first('hub-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Organization -->
					<div class="row form-group{{ ($errors->has('hub-organization')) ? ' has-error' : '' }}">
						<div class="hub-organization-type-label col-sm-4 col-xs-12">
							<label for="hub-organization">Организација:</label>
						</div>
						<div class="hub-organization-value col-sm-8 col-xs-12">
							<select id="hub-organization" class="form-control" name="hub-organization">
								<option value="">-- Избери --</option>

								@foreach ($organizations as $organization)
									<option value={{$organization->id}}
										{{ (old('hub-organization') == $organization->id) ? ' selected' : (($hub->organization_id == $organization->id) ? ' selected' : '')}}>{{$organization->name}}</option>
								@endforeach
							</select>
							 @if ($errors->has('hub-organization'))
							<span class="help-block">
									<strong>{{ $errors->first('hub-organization') }}</strong>
							</span>
							@endif
						</div>
					</div>

					<!-- Region -->
					<div class="row form-group{{ ($errors->has('hub-region')) ? ' has-error' : '' }}">
						<div class="hub-region-type-label col-sm-4 col-xs-12">
							<label for="hub-region">Регион:</label>
						</div>
						<div class="hub-region-value col-sm-8 col-xs-12">
							<select id="hub-region" class="form-control" name="hub-region">
								<option value="">-- Избери --</option>
								@foreach ($regions as $region)
									<option value={{$region->id}}
										{{ (old('hub-region') == $region->id) ? ' selected' : (($hub->region_id == $region->id) ? ' selected' : '')}}>{{$region->name}}</option>
								@endforeach
							</select>
							 @if ($errors->has('hub-region'))
							<span class="help-block">
									<strong>{{ $errors->first('hub-region') }}</strong>
							</span>
							@endif
						</div>
					</div>


					<!-- First name -->
					<div class="row form-group{{ ($errors->has('hub-first-name')) ? ' has-error' : '' }}">
						<div class="hub-first-name-label col-sm-4 col-xs-12">
							<label for="hub-first-name">Име:</label>
						</div>
						<div class="hub-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="hub-first-name" class="form-control" value="{{ (old('hub-first-name')) ? old('hub-first-name') : $hub->first_name }}" >
							@if ($errors->has('hub-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('hub-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('hub-last-name')) ? ' has-error' : '' }}">
						<div class="hub-last-name-label col-sm-4 col-xs-12">
							<label for="hub-last-name">Презиме:</label>
						</div>
						<div class="hub-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="hub-last-name" class="form-control" value="{{ (old('hub-last-name')) ? old('hub-last-name') : $hub->last_name }}" required>
							@if ($errors->has('hub-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('hub-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Address -->
					<div class="row form-group{{ ($errors->has('hub-address')) ? ' has-error' : '' }}">
						<div class="hub-address-label col-sm-4 col-xs-12">
							<label for="hub-address">Адреса:</label>
						</div>
						<div class="hub-address-value col-sm-8 col-xs-12">
							<input type="address" name="hub-address" class="form-control"
										value="{{ (old('hub-address')) ? old('hub-address') : $hub->address }}">
							@if ($errors->has('hub-address'))
								<span class="help-block">
									<strong>{{ $errors->first('hub-address') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('hub-phone')) ? ' has-error' : '' }}">
						<div class="hub-phone-label col-sm-4 col-xs-12">
							<label for="hub-phone">Телефон:</label>
						</div>
						<div class="hub-phone-value col-sm-8 col-xs-12">
							<input type="text" name="hub-phone" class="form-control" value="{{ (old('hub-phone')) ? old('hub-phone') : $hub->phone }}" required>
							@if ($errors->has('hub-phone'))
								<span class="help-block">
									<strong>{{ $errors->first('hub-phone') }}</strong>
								</span>
							@endif
						</div>
					</div>


				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-hub-submit" type="submit" name="edit-hub-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.hub_users')}}" id="cancel-edit-hub" name="cancel-edit-hub"
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

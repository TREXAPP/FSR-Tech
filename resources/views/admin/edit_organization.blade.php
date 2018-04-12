@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header organization-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени организација</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Admin</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/organizations">
				<i class="fa fa-user-circle"></i> Организации</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/organizations/"{{$organization->id}}>Измени</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content organization-content edit-organization-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-organization-box organization-box box">
	<form id="edit-organization-form" class="" action="{{ route('admin.edit_organization',$organization->id) }}" method="post" enctype="multipart/form-data">
		<input type="hidden" name="organization_id" value="{{$organization->id}}">
            {{ csrf_field() }}
		<div class="organization-box-body-wrapper">
			<div class="box-body">
				<div id="organization-image" class="col-md-4 col-xs-12 organization-image edit-layout-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="{{$organization->first_name}}"
									src="{{Methods::get_organization_image_url($organization)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('organization-image')) ? ' has-error' : '' }}">
							<label for="organization-image">Измени слика:</label>
              <input id="organization-image" type="file" class="form-control" name="organization-image" value="{{ old('organization-image') }}">
							@if ($errors->has('organization-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('organization-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="organization-info" class="col-md-8 col-xs-12 organization-info">

					<!-- Organization type -->
					<div class="row form-group{{ ($errors->has('organization-type')) ? ' has-error' : '' }}">
						<div class="organization-type-label col-sm-4 col-xs-12">
							<label>Тип на организација:</label>
						</div>
						<div class="organization-type-value col-sm-8 col-xs-12">
							<label>{{($organization->type == 'donor') ? 'Донатор' : 'Примател'}}</label>
							<input type="hidden" name="organization-type" value="{{$organization->type}}">
						</div>
					</div>

					<!-- Donor type -->
					@if ($organization->type == 'donor')
						<div class="row form-group{{ ($errors->has('organization-donor-type')) ? ' has-error' : '' }}">
							<div class="organization-donor-type-label col-sm-4 col-xs-12">
								<label for="organization-donor-type">Тип на донатор:</label>
							</div>
							<div class="organization-donor-type-value col-sm-8 col-xs-12">
								<select id="organization-donor-type" class="form-control" name="organization-donor-type">
									<option value="">-- Избери --</option>
									@foreach ($donor_types as $donor_type)
										<option value={{$donor_type->id}}
											{{ (old('donor_type') == $donor_type->id) ? ' selected' : (($organization->donor_type_id == $donor_type->id) ? ' selected' : '')}}>{{$donor_type->name}}</option>
									@endforeach
								</select>
								 @if ($errors->has('organization-donor-type'))
								<span class="help-block">
										<strong>{{ $errors->first('organization-donor-type') }}</strong>
								</span>
								@endif
							</div>
						</div>
					@endif

					<!-- Name -->
					<div class="row form-group{{ ($errors->has('organization-name')) ? ' has-error' : '' }}">
						<div class="organization-name-label col-sm-4 col-xs-12">
							<label for="organization-name">Име:</label>
						</div>
						<div class="organization-name-value col-sm-8 col-xs-12">
							<input type="text" name="organization-name" class="form-control"
										value="{{ (old('organization-name')) ? old('organization-name') : $organization->name }}" >
							@if ($errors->has('organization-name'))
								<span class="help-block">
									<strong>{{ $errors->first('organization-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Address -->
					<div class="row form-group{{ ($errors->has('organization-address')) ? ' has-error' : '' }}">
						<div class="organization-address-label col-sm-4 col-xs-12">
							<label for="organization-address">Адреса:</label>
						</div>
						<div class="organization-address-value col-sm-8 col-xs-12">
							<input type="text" name="organization-address" class="form-control"
										value="{{ (old('organization-address')) ? old('organization-address') : $organization->address }}" >
							@if ($errors->has('organization-address'))
								<span class="help-block">
									<strong>{{ $errors->first('organization-address') }}</strong>
								</span>
							@endif
						</div>
					</div>


					<!-- Description -->
					<div class="row form-group{{ ($errors->has('organization-description')) ? ' has-error' : '' }}">
						<div class="organization-description-label col-sm-4 col-xs-12">
							<label for="organization-description">Опис:</label>
						</div>
						<div class="organization-description-value col-sm-8 col-xs-12">

							<textarea rows="4" form="edit-organization-form" id="organization-description" class="form-control"
												name="organization-description" >{{ (old('organization-description')) ? old('organization-description') : $organization->description }}</textarea>
							@if ($errors->has('organization-description'))
								<span class="help-block">
									<strong>{{ $errors->first('organization-description') }}</strong>
								</span>
							@endif
						</div>
					</div>


					@if ($organization->type == 'donor')

						<!-- working_hours_from -->
					<div class="row working-hours-from form-group{{ ($errors->has('working_hours_from')) ? ' has-error' : '' }}">
						<div class="organization-working-hours-from-label col-sm-4 col-xs-12">
							<label for="organization-working-hours-from">Работно време од:</label>
						</div>
						<div class="organization-address-value col-sm-8 col-xs-12">
							<div>
								<input id="working_hours_from" type="time" step="3600" name="working_hours_from" class="form-control"
											value="{{ (old('working_hours_from')) ? old('working_hours_from') : $organization->working_hours_from }}" >
							</div>
							<div class="col-xs-6" style="padding-right: 0px;">
								<span>часот</span>
							</div>
							@if ($errors->has('working_hours_from'))
								<span class="help-block">
									<strong>{{ $errors->first('working_hours_from') }}</strong>
								</span>
							@endif
						</div>
					</div>


						<!-- working_hours_to -->
						<div class="row working-hours-to form-group{{ ($errors->has('working_hours_to')) ? ' has-error' : '' }}">
							<div class="organization-working-hours-to-label col-sm-4 col-xs-12">
								<label for="organization-working-hours-to">Работно време до:</label>
							</div>
							<div class="organization-address-value col-sm-8 col-xs-12">
								<div>
									<input id="working_hours_to" type="time" step="3600" name="working_hours_to" class="form-control"
												value="{{ (old('working_hours_to')) ? old('working_hours_to') : $organization->working_hours_to }}" >
								</div>
								<div class="col-xs-6" style="padding-right: 0px;">
									<span>часот</span>
								</div>
								@if ($errors->has('working_hours_to'))
									<span class="help-block">
										<strong>{{ $errors->first('working_hours_to') }}</strong>
									</span>
								@endif
							</div>
						</div>

					@endif




				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-organization-submit" type="submit" name="edit-organization-submit" class="btn btn-success" >Измени</button>
				<a href="{{route('admin.' . $organization->type . '_organizations')}}"
						id="cancel-edit-organization" name="cancel-edit-organization"
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

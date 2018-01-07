@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header volunteer-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Волонтери во {{Auth::user()->organization->name}}</span>
		@if ($volunteers->count() > 0)
		<span> ({{$volunteers->count()}})</span>
		@endif
		<a href="{{route('cso.new_volunteer')}}" id="new-volunteer-button" name="new-volunteer-button"
		class="btn btn-success new-volunteer-button"><i class="fa fa-plus"></i>Додади нов волонтер</a>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Примател</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/volunteers">
				<i class="fa fa-universal-access"></i> Волонтери</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content volunteer-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($volunteers->get() as $volunteer)



	<!-- Default box -->
<div class="section-wrapper volunteers-section-wrapper col-md-6">
<div id="volunteerbox{{$volunteer->id}}" name="volunteerbox{{$volunteer->id}}"></div>
	<div class="cso-volunteer-box box volunteer-box volunteer-box-{{$volunteer->id}} {{($volunteer->id == old('volunteer_id')) ? 'box-error' : 'collapsed-box' }}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="volunteer-image-preview-{{$volunteer->id}}" class="volunteer-image-preview">
					@if ($volunteer->image_id)
						<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($volunteer->image_id)->filename)}}" />
					@else
						<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{url('img/avatar5.png')}}" />
					@endif
				</div>
				<div class="header-wrapper">
					<div id="volunteer-name-{{$volunteer->id}}" class="volunteer-name">
						<span class="volunteer-listing-title">{{$volunteer->first_name}} {{$volunteer->last_name}}</span>
					</div>
					<div class="box-tools pull-right">
							<i class="fa fa-caret-down pull-right"></i>
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="volunteer-image-wrapper-{{$volunteer->id}}" class="volunteer-image-wrapper col-md-4">


									@if ($volunteer->image_id)
										<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{url('storage' . config('app.upload_path') . '/' . FSR\File::find($volunteer->image_id)->filename)}}" />
									@else
										<img class="img-rounded" alt="{{$volunteer->first_name}}" src="{{url('img/avatar5.png')}}" />
									@endif

				</div>

				<div id="volunteer-info-wrapper-{{$volunteer->id}}" class="volunteer-info-wrapper col-md-8">

					<!-- First Name -->
					<div id="volunteer-info-first-name-{{$volunteer->id}}" class="row volunteer-info-first-name row">
						<div id="volunteer-info-first-name-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-first-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="volunteer-info-first-name-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-first-name-value col-md-8">
							<span><strong>{{$volunteer->first_name}}</strong></span>
						</div>
					</div>


				<!-- Last Name -->
					<div id="volunteer-info-last-name-{{$volunteer->id}}" class="row volunteer-info-last-name row">
						<div id="volunteer-info-last-name-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-last-name-label col-md-4">
							<span>Презиме:</span>
						</div>
						<div id="volunteer-info-last-name-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-last-name-value col-md-8">
							<span><strong>{{$volunteer->last_name}}</strong></span>
						</div>
					</div>

				<!-- Email -->
					<div id="volunteer-info-email-{{$volunteer->id}}" class="row volunteer-info-email row">
						<div id="volunteer-info-email-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-email-label col-md-4">
							<span>Емаил:</span>
						</div>
						<div id="volunteer-info-email-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-email-value col-md-8">
							<span><strong>{{$volunteer->email}}</strong></span>
						</div>
					</div>

				<!-- Phone -->
					<div id="volunteer-info-phone-{{$volunteer->id}}" class="row volunteer-info-phone row">
						<div id="volunteer-info-phone-label-{{$volunteer->id}}" class="volunteer-info-label volunteer-info-phone-label col-md-4">
							<span>Телефон:</span>
						</div>
						<div id="volunteer-info-phone-value-{{$volunteer->id}}" class="volunteer-info-value volunteer-info-phone-value col-md-8">
							<span><strong>{{$volunteer->phone}}</strong></span>
						</div>
					</div>

				</div>

			</div>
			<div class="box-footer">
				<div class="pull-right">
					<a href="{{url('cso/volunteers/' . $volunteer->id)}}" id="edit-volunteer-button-{{$volunteer->id}}" name="edit-volunteer-button-{{$volunteer->id}}"
					class="btn btn-success edit-volunteer-button">Измени ги податоците</a>
					<button id="delete-volunteer-button-{{ $volunteer->id }}" type="submit" data-toggle="modal" data-target="#delete-volunteer-popup"
									name="delete-volunteer-button" class="btn btn-danger delete-volunteer-button" >Избриши го волонтерот</button>
				</div>
			</div>
		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-volunteer-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-volunteer-form" class="delete-volunteer-form" action="{{ route('cso.volunteers') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го волонтерот</h4>
				</div>
				<div id="delete-volunteer-body" class="modal-body delete-volunteer-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите волонтерот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="submit" name="delete-volunteer-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

	<!-- Add volunteer Modal -->
	<div id="add-volunteer-popup" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<form id="add-volunteer-form" class="add-volunteer-form" action="{{ route('cso.active_listings.add_volunteer') }}" method="post"
				 enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 id="popup-title" class="modal-title popup-title">Нов Волонтер</h4>
					</div>
					<div id="add-volunteer-body" class="modal-body add-volunteer-body">
						<!-- Form content-->
						<h5 id="popup-info" class="popup-info row italic">
							Внесете ги податоците за волонтерот:
						</h5>

						<!-- first name -->
						<div id="first-name-form-group" class="form-group row">
							<label for="first_name" class="col-md-2 col-md-offset-2 control-label">Име:</label>
							<div class="col-md-6">
								<input id="first_name" type="text" class="form-control" name="first_name" {{-- value="" style="text-align: center;" required> --}} value="" style="text-align: center;" >
								<span id="first-name-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- last name -->
						<div id="last-name-form-group" class="form-group row">
							<label for="last_name" class="col-md-2 col-md-offset-2 control-label">Презиме:</label>
							<div class="col-md-6">
								<input id="last_name" type="text" class="form-control" name="last_name" value="" style="text-align: center;"> {{-- value="" style="text-align: center;" required > --}}
								<span id="last-name-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- email -->
						<div id="email-form-group" class="form-group row">
							<label for="email" class="col-md-2 col-md-offset-2 control-label">Емаил:</label>
							<div class="col-md-6">
								<input id="email" type="email" class="form-control" name="email" {{-- value="" style="text-align: center;" required> --}} value="" style="text-align: center;" >
								<span id="email-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- email -->
						<div id="phone-form-group" class="form-group row">
							<label for="phone" class="col-md-2 col-md-offset-2 control-label">Контакт:</label>
							<div class="col-md-6">
								<input id="phone" type="text" class="form-control" name="phone" value="" style="text-align: center;"> {{-- value="" style="text-align: center;" required > --}}
								<span id="phone-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

						<!-- Upload image -->
						<div id="image-form-group" class="form-group{{ $errors->has('image') ? ' has-error' : '' }} row">
							<label for="image" class="col-md-2 col-md-offset-2 control-label">Слика</label>

							<div class="col-md-6">
								<input id="image" type="file" class="form-control" name="image" value="{{ old('image') }}">
								<span id="image-error" class="help-block" style="font-weight: bold;">
									<strong></strong>
								</span>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						{{--
						<i id="popup-loading" class="fa fa-spinner fa-pulse fa-2x fa-fw"></i> --}}
						<i id="popup-loading" class="popup-loading"></i>
						<input type="submit" id="add-volunteer-popup-submit" name="add-volunteer-popup-submit" class="btn btn-primary" value="Прифати"
						/>
						<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
					</div>
				</form>
			</div>
		</div>
	</div>


</section>
<!-- /.content -->

@endsection

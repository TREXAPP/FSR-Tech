@extends('layouts.admin_master')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header location-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Прати емаил </span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="{{route('admin.email')}}">
				<i class="fa fa-universal-access"></i> Прати емаил</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content email-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	<form id="email-form" class="email-form" action="{{route('admin.email')}}" name="email-form" method="post">
		{{ csrf_field() }}
	<!-- Filter -->
	<section class="filter email-filter">
		<div class="filter-wrapper form-group row">

			<!-- user types filter -->
			<div class="filter-container col-md-4">
				<div class="filter-label email-filter-label col-xs-12">
					<label for="user-type-filter-select">Типови на корисници:</label>
				</div>
				<div class="filter-select email-filter-select col-xs-12">
					<select id="user-type-filter-select" class="form-control user-type-filter-select" name="user-type-filter-select">
						<option value="">-- Сите --</option>
						<option value="donors">Донори</option>
						<option value="csos">Приматели</option>
						<option value="volunteers">Волонтери</option>
					</select>
					@if ($errors->has('user-type-filter-select'))
						<span class="help-block">
							<strong>{{ $errors->first('user-type-filter-select') }}</strong>
						</span>
					@endif
				</div>
			</div>

			<!-- organizations filter -->
			<div class="filter-container col-md-4">
				<div class="filter-label email-filter-label col-xs-12">
					<label for="organization-filter-select">Организација:</label>
				</div>
				<div class="filter-select email-filter-select col-xs-12">
					<select id="organization-filter-select" class="form-control organization-filter-select"
									name="organization-filter-select"
									disabled>
						<option value="">-- Сите --</option>
					</select>
					@if ($errors->has('organization-filter-select'))
						<span class="help-block">
							<strong>{{ $errors->first('organization-filter-select') }}</strong>
						</span>
					@endif
				</div>
			</div>

			<!-- user filter -->
			<div class="filter-container col-md-4">
				<div class="filter-label email-filter-label col-xs-12">
					<label for="user-filter-select">Корисник:</label>
				</div>
				<div class="filter-select email-filter-select col-xs-12">
					<select id="user-filter-select" class="form-control user-filter-select" name="user-filter-select"
								disabled >
						<option value="">-- Сите --</option>
					</select>
					@if ($errors->has('user-filter-select'))
						<span class="help-block">
							<strong>{{ $errors->first('user-filter-select') }}</strong>
						</span>
					@endif
				</div>
			</div>
	</div>
	</section>

	<div class="panel">
		Селектирани
		<span id="donors-counter">{{(old('user-type-filter-select')) ? old('user-type-filter-select') : $donors->count()}}</span> донори,
		<span id="csos-counter">{{(old('organization-filter-select')) ? old('organization-filter-select') : $csos->count()}}</span> приматели и
		<span id="volunteers-counter">{{(old('user-filter-select')) ? old('user-filter-select') : $volunteers->count()}}</span> волонтери
	</div>


	<!-- Default box -->
<section class="message email-message">
	<div class="section-wrapper emails-section-wrapper">
		<div class="admin-email-box box email-box two-col-layout-box">

			<div class="listing-box-body-wrapper">
				<div class="box-body">

					<div id="email-info-wrapper" class="email-info-wrapper two-col-layout-info-wrapper col-md-12">

						<!-- Subject -->
						<div id="email-info-subject" class="row email-info-subject form-group row
						{{($errors->has('email-subject')) ? 'has-error' : ''}}">
							<div id="email-info-subject-label" class="email-info-label email-info-subject-label col-md-2">
								<span>Наслов:</span>
							</div>
							<div id="email-info-subject-value" class="email-info-value email-info-subject-value col-md-10">
								<input id="email-subject" name="email-subject"  type="text" class="form-control" />
								@if ($errors->has('email-subject'))
								<span class="help-block">
										<strong>{{ $errors->first('email-subject') }}</strong>
								</span>
								@endif
							</div>
						</div>

						<!-- Message -->
						<div id="email-info-message" class="row email-info-message form-group row
									{{($errors->has('email-message')) ? 'has-error' : ''}}">
							<div id="email-info-message-label" class="email-info-label email-info-message-label col-md-2">
								<span>Порака:</span>
							</div>
							<div id="email-info-message-value" class="email-info-value email-info-message-value col-md-10">
								<textarea type="text" class="form-control" id="email-message" name="email-message"
								 			form="email-form"  rows="4" cols="50" ></textarea>
								@if ($errors->has('email-message'))
								<span class="help-block">
										<strong>{{ $errors->first('email-message') }}</strong>
								</span>
								@endif
							</div>
						</div>

					</div>

				</div>

				<div class="box-footer">
						<div class="pull-right">
							<input type="hidden" name="post-type" value="email" />
							<input type="submit" id="send-email-button" name="send-email-button" class="btn btn-primary send-email-button" value="Прати" />
							</div>
				</div>

			</div>

			<!-- /.box-footer-->
		</div>
		<!-- /.box -->

	</div>
</section>
</form>

<!-- Delete Modal  -->
<div id="send-email-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="send-email-form" class="send-email-form" action="{{ route('admin.email') }}" method="post">
				{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Прати емаил</h4>
				</div>
				<div id="send-email-body" class="modal-body send-email-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го пратите емаилот на избраните корисници?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="send-email-popup" class="btn btn-primary" value="Прати" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>


</section>
<!-- /.content -->

@endsection

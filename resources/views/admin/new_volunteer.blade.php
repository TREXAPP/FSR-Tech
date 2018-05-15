@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нов доставувач
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_location"><i class="fa fa-plus-circle"></i> Додади нов доставувач</a></li>
    </ol>
  </section>

<!-- Main content -->
<section class="content new-volunteer-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="new-volunteer-box box">
		<!--
		<div class="box-header with-border">

			<div id="new-volunteer-title" class="new-volunteer-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="new-volunteer-form" class="" action="{{ route('admin.new_volunteer') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
		<div class="volunteer-box-body-wrapper">
			<div class="box-body">
				<div id="new-volunteer-image" class="col-md-4 col-xs-12 new-volunteer-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="Слика за доставувач" src="{{Methods::get_volunteer_image_url(null)}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('volunteer-image')) ? ' has-error' : '' }}">
							<label for="new-volunteer-image">Внеси слика:</label>
              <input id="new-volunteer-image" type="file" class="form-control" name="volunteer-image" value="{{ old('volunteer-image') }}">
							@if ($errors->has('volunteer-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('volunteer-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="new-volunteer-info" class="col-md-8 col-xs-12 new-volunteer-info">

					<!-- CSO Organization -->
					<div class="row form-group{{$errors->has('volunteer-organization') ? ' has-error' : '' }}">
						<div class="new-volunteer-organization-label col-sm-4 col-xs-12">
							<label for="new-volunteer-organization">Организација:</label>
						</div>
						<div class="new-volunteer-organization-value col-sm-8 col-xs-12">
              <select class="form-control" name="volunteer-organization" required>
                <option value="">-- Избери --</option>
                @foreach ($organizations as $organization)
                  <option value="{{$organization->id}}" {{ (old('volunteer-organization')== $organization->id) ? ' selected' : '' }}>{{$organization->name}}</option>
                @endforeach
              </select>
              <!-- TUKA TREBA DA SE STAVI CHECKBOX ZA GLOBALEN VOLONTER! -->
							@if ($errors->has('volunteer-organization'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-organization') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- First name -->
					<div class="row form-group{{ ($errors->has('volunteer-first-name')) ? ' has-error' : '' }}">
						<div class="new-volunteer-first-name-label col-sm-4 col-xs-12">
							<label for="new-volunteer-first-name">Име:</label>
						</div>
						<div class="new-volunteer-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-first-name" class="form-control" value="{{ (old('volunteer-first-name')) ? old('volunteer-first-name') : '' }}" required>
							@if ($errors->has('volunteer-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('volunteer-last-name')) ? ' has-error' : '' }}">
						<div class="new-volunteer-last-name-label col-sm-4 col-xs-12">
							<label for="new-volunteer-last-name">Презиме:</label>
						</div>
						<div class="new-volunteer-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-last-name" class="form-control" value="{{ (old('volunteer-last-name')) ? old('volunteer-last-name') : '' }}" required>
							@if ($errors->has('volunteer-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>


					<div class="row form-group{{ ($errors->has('volunteer-email')) ? ' has-error' : '' }}">
						<div class="new-volunteer-email-label col-sm-4 col-xs-12">
							<label for="new-volunteer-email">Емаил:</label>
						</div>
						<div class="new-volunteer-email-value col-sm-8 col-xs-12">
							<input type="email" name="volunteer-email" class="form-control" value="{{ (old('volunteer-email')) ? old('volunteer-email') : '' }}" required>
							@if ($errors->has('volunteer-email'))
								<span class="help-block">
									<strong>{{ $errors->first('volunteer-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<div class="row form-group{{ ($errors->has('volunteer-phone')) ? ' has-error' : '' }}">
						<div class="new-volunteer-phone-label col-sm-4 col-xs-12">
							<label for="new-volunteer-phone">Телефон:</label>
						</div>
						<div class="new-volunteer-phone-value col-sm-8 col-xs-12">
							<input type="text" name="volunteer-phone" class="form-control" value="{{ (old('volunteer-phone')) ? old('volunteer-phone') : '' }}" required>
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
				<button id="new-volunteer-submit" type="submit" name="new-volunteer-submit" class="btn btn-primary" >Внеси</button>
			</div>
		</div>
	</form>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->


</section>
<!-- /.content -->


@endsection

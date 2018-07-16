@extends('layouts.admin_master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1><i class="fa fa-plus-circle"></i>
      Додади нов администратор
    </h1>
    <ol class="breadcrumb hidden-sm hidden-xs">
      <li><a href="/{{Auth::user()->type()}}/home"> Админ</a></li>
      <li><a href="/{{Auth::user()->type()}}/new_location"><i class="fa fa-plus-circle"></i> Додади нов администратор</a></li>
    </ol>
  </section>

<!-- Main content -->
<section class="content new-admin-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="new-admin-box box">
		<!--
		<div class="box-header with-border">

			<div id="new-admin-title" class="new-admin-title col-xs-12">
				{{-- <span class="pull-right"><strong>{{$user->email}} | {{$user->organization->name}}</strong></span> --}}
			</div>

		</div>
	-->
	<form id="new-admin-form" class="" action="{{ route('master_admin.new_admin') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
		<div class="admin-box-body-wrapper">
			<div class="box-body">
				<div id="new-admin-image" class="col-md-4 col-xs-12 new-admin-image">
					<div class="col-xs-12 form-group">
							<img class="img-rounded" alt="Слика за администратор" src="{{url('img/admin.png')}}" />
					</div>
					<div class="col-xs-12 form-group {{ ($errors->has('admin-image')) ? ' has-error' : '' }}">
							<label for="new-admin-image">Внеси слика:</label>
              <input id="new-admin-image" type="file" class="form-control" name="admin-image" value="{{ old('admin-image') }}">
							@if ($errors->has('admin-image'))
						 <span class="help-block">
								 <strong>{{ $errors->first('admin-image') }}</strong>
						 </span>
		 				 @endif
					</div>
				</div>

				<div id="new-admin-info" class="col-md-8 col-xs-12 new-admin-info">

					<!-- First name -->
					<div class="row form-group{{ ($errors->has('admin-first-name')) ? ' has-error' : '' }}">
						<div class="new-admin-first-name-label col-sm-4 col-xs-12">
							<label for="new-admin-first-name">Име:</label>
						</div>
						<div class="new-admin-first-name-value col-sm-8 col-xs-12">
							<input type="text" name="admin-first-name" class="form-control" value="{{ (old('admin-first-name')) ? old('admin-first-name') : '' }}" required>
							@if ($errors->has('admin-first-name'))
								<span class="help-block">
									<strong>{{ $errors->first('admin-first-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

					<!-- Last name -->
					<div class="row  form-group{{ ($errors->has('admin-last-name')) ? ' has-error' : '' }}">
						<div class="new-admin-last-name-label col-sm-4 col-xs-12">
							<label for="new-admin-last-name">Презиме:</label>
						</div>
						<div class="new-admin-last-name-value col-sm-8 col-xs-12">
							<input type="text" name="admin-last-name" class="form-control" value="{{ (old('admin-last-name')) ? old('admin-last-name') : '' }}" required>
							@if ($errors->has('admin-last-name'))
								<span class="help-block">
									<strong>{{ $errors->first('admin-last-name') }}</strong>
								</span>
							@endif
						</div>
					</div>

          	<!-- Email -->
					<div class="row form-group{{ ($errors->has('admin-email')) ? ' has-error' : '' }}">
						<div class="new-admin-email-label col-sm-4 col-xs-12">
							<label for="new-admin-email">Емаил:</label>
						</div>
						<div class="new-admin-email-value col-sm-8 col-xs-12">
							<input type="email" name="admin-email" class="form-control" value="{{ (old('admin-email')) ? old('admin-email') : '' }}" required>
							@if ($errors->has('admin-email'))
								<span class="help-block">
									<strong>{{ $errors->first('admin-email') }}</strong>
								</span>
							@endif
						</div>
					</div>

          	<!-- Password -->
					<div class="row form-group{{ ($errors->has('admin-password')) ? ' has-error' : '' }}">
						<div class="new-admin-password-label col-sm-4 col-xs-12">
							<label for="new-admin-password">Лозинка:</label>
						</div>
						<div class="new-admin-password-value col-sm-8 col-xs-12">
							<input type="password" name="admin-password" class="form-control" value="" required>
							@if ($errors->has('admin-password'))
								<span class="help-block">
									<strong>{{ $errors->first('admin-password') }}</strong>
								</span>
							@endif
						</div>
					</div>

          	<!-- confirm-password -->
					<div class="row form-group{{ ($errors->has('admin-password_confirmation')) ? ' has-error' : '' }}">
						<div class="new-admin-confirm-password-label col-sm-4 col-xs-12">
							<label for="new-admin-confirm-password">Повтори лозинка:</label>
						</div>
						<div class="new-admin-confirm-password-value col-sm-8 col-xs-12">
							<input type="password" name="admin-password_confirmation" class="form-control" value="" required>
							@if ($errors->has('admin-confirm-password'))
								<span class="help-block">
									<strong>{{ $errors->first('admin-password_confirmation') }}</strong>
								</span>
							@endif
						</div>
					</div>



				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="new-admin-submit" type="submit" name="new-admin-submit" class="btn btn-primary" >Внеси</button>
			</div>
		</div>
	</form>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->


</section>
<!-- /.content -->


@endsection

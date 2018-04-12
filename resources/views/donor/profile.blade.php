@extends('layouts.master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header active-listings-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Кориснички профил</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Донатор</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/profile">
				<i class="fa fa-user-circle"></i> Кориснички профил</a>
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

		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="profile-image-wrapper" class="col-md-4 col-xs-12 profile-image">
						<img class="img-rounded" alt="{{$user->first_name}}" src="{{Methods::get_user_image_url(Auth::user())}}" />
				</div>
				<div id="profile-info" class="col-md-8 col-xs-12 profile-info">

					<div class="row">
						<div class="profile-first-name-label col-sm-4 col-xs-12">
							<span class="">Име:</span>
						</div>
						<div class="profile-first-name-value col-sm-8 col-xs-12">
							<span><strong>{{$user->first_name}}</strong></span>
						</div>
					</div>

					<div class="row">
						<div class="profile-last-name-label col-sm-4 col-xs-12">
							<span class="">Презиме:</span>
						</div>
						<div class="profile-last-name-value col-sm-8 col-xs-12">
							<span><strong>{{$user->last_name}}</strong></span>
						</div>
					</div>

					<div class="row">
						<div class="profile-email-label col-sm-4 col-xs-12">
							<span class="">Емаил:</span>
						</div>
						<div class="profile-email-value col-sm-8 col-xs-12">
							<span><strong>{{$user->email}}</strong></span>
						</div>
					</div>

					<div class="row">
						<div class="profile-organization-label col-sm-4 col-xs-12">
							<span class="">Организација:</span>
						</div>
						<div class="profile-organization-value col-sm-8 col-xs-12">
							<span><strong>{{$user->organization->name}}</strong></span>
						</div>
					</div>

					<div class="row">
						<div class="profile-address-label col-sm-4 col-xs-12">
							<span class="">Адреса:</span>
						</div>
						<div class="profile-address-value col-sm-8 col-xs-12">
							<span><strong>{{$user->address}}</strong></span>
						</div>
					</div>

					<div class="row">
						<div class="profile-phone-label col-sm-4 col-xs-12">
							<span class="">Телефон:</span>
						</div>
						<div class="profile-phone-value col-sm-8 col-xs-12">
							<span><strong>{{$user->phone}}</strong></span>
						</div>
					</div>

					<div class="row">
						<div class="profile-location-label col-sm-4 col-xs-12">
							<span class="">Локација:</span>
						</div>
						<div class="profile-location-value col-sm-8 col-xs-12">
							<span><strong>{{$user->location->name}}</strong></span>
						</div>
					</div>

				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<a href="{{url('donor/edit_profile')}}" id="edit-profile-button" name="edit-profile-button"
			 class="btn btn-primary listing-submit-button pull-right">Измени профил</a>
		</div>


		<!-- /.box-footer-->
	</div>
	<!-- /.box -->


</section>
<!-- /.content -->

@endsection

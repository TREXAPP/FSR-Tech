@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<section class="content-header admin-content-header">
	<h1>
		<i class="fa fa-universal-access"></i>
		<span>Администратори</span>
		@if ($admins->count() > 0)
		<span> ({{$admins->count()}})</span>
		@endif
		<a href="{{route('master_admin.new_admin')}}" id="new-admin-button" name="new-admin-button"
		class="btn btn-success new-admin-button"><i class="fa fa-plus"></i>Додади нов администратор</a>
		{{-- <a href="{{route('master_admin.new_admin')}}" id="new-admin-button" name="new-admin-button"
		class="btn btn-success new-admin-button" disabled><i class="fa fa-plus"></i>Додади нов администратор</a> --}}
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="/{{Auth::user()->type()}}/home"> Админ</a>
		</li>
		<li>
			<a href="/{{Auth::user()->type()}}/admins">
				<i class="fa fa-universal-access"></i> Администратори</a>
		</li>
	</ol>
</section>

<!-- Main content -->
<section class="content admin-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif

	@foreach ($admins as $admin)



	<!-- Default box -->
<div class="section-wrapper admins-section-wrapper col-md-6">
<div id="adminbox{{$admin->id}}" name="adminbox{{$admin->id}}"></div>
	<div class="admin-admin-box box admin-box two-col-layout-box admin-box-{{$admin->id}} {{($admin->id == old('admin_id')) ? 'box-error' : 'collapsed-box' }} {{($admin->is_user) ? ' admin-is-user' : ''}}">
		<div class="box-header with-border listing-box-header">
			<a href="#" class=" btn-box-tool listing-box-anchor" data-widget="collapse" data-toggle="tooltip" style="display: block;">

				<div id="admin-image-preview-{{$admin->id}}" class="admin-image-preview two-col-layout-image-preview">
					<img class="img-rounded" alt="{{$admin->first_name}}" src="{{Methods::get_user_image_url($admin)}}" />
				</div>
				<div class="header-wrapper">
					<div id="admin-name-{{$admin->id}}" class="admin-name">
						<span class="admin-listing-title two-col-layout-listing-title">{{$admin->first_name}} {{$admin->last_name}}</span>
					</div>

					<div class="box-tools pull-right">
						<span class="add-more">Повеќе...</span>
							{{-- <i class="fa fa-caret-down pull-right"></i> --}}
					</div>
				</div>

			</a>
		</div>
		<div class="listing-box-body-wrapper">
			<div class="box-body">
				<div id="admin-image-wrapper-{{$admin->id}}" class="admin-image-wrapper two-col-layout-image-wrapper col-md-4">
					<img class="img-rounded" alt="{{$admin->first_name}}" src="{{Methods::get_user_image_url($admin)}}" />
				</div>

				<div id="admin-info-wrapper-{{$admin->id}}" class="admin-info-wrapper two-col-layout-info-wrapper col-md-8">

					<!-- First Name -->
					<div id="admin-info-first-name-{{$admin->id}}" class="row admin-info-first-name row">
						<div id="admin-info-first-name-label-{{$admin->id}}" class="admin-info-label admin-info-first-name-label col-md-4">
							<span>Име:</span>
						</div>
						<div id="admin-info-first-name-value-{{$admin->id}}" class="admin-info-value admin-info-first-name-value col-md-8">
							<span><strong>{{$admin->first_name}}</strong></span>
						</div>
					</div>


				<!-- Last Name -->
					<div id="admin-info-last-name-{{$admin->id}}" class="row admin-info-last-name row">
						<div id="admin-info-last-name-label-{{$admin->id}}" class="admin-info-label admin-info-last-name-label col-md-4">
							<span>Презиме:</span>
						</div>
						<div id="admin-info-last-name-value-{{$admin->id}}" class="admin-info-value admin-info-last-name-value col-md-8">
							<span><strong>{{$admin->last_name}}</strong></span>
						</div>
					</div>

				<!-- Email -->
					<div id="admin-info-email-{{$admin->id}}" class="row admin-info-email row">
						<div id="admin-info-email-label-{{$admin->id}}" class="admin-info-label admin-info-email-label col-md-4">
							<span>Емаил:</span>
						</div>
						<div id="admin-info-email-value-{{$admin->id}}" class="admin-info-value admin-info-email-value col-md-8">
							<span><strong>{{$admin->email}}</strong></span>
						</div>
					</div>

				</div>

			</div>
			@if (!$admin->is_user)

			<div class="box-footer">
					<div class="pull-right">
						<a href="{{route('master_admin.edit_admin', $admin->id)}}" id="edit-admin-button-{{$admin->id}}" name="edit-admin-button-{{$admin->id}}"
							class="btn btn-success edit-admin-button">Измени ги податоците</a>
						{{-- <a href="{{route('master_admin.edit_admin', $admin->id)}}" id="edit-admin-button-{{$admin->id}}" name="edit-admin-button-{{$admin->id}}"
							class="btn btn-success edit-admin-button">Измени ги податоците</a> --}}
							<button id="delete-admin-button-{{ $admin->id }}" type="submit" data-toggle="modal" data-target="#delete-admin-popup"
								name="delete-admin-button" class="btn btn-danger delete-admin-button" >Избриши го администраторот</button>
							</div>
			</div>
			@endif
		</div>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->
</div>


@endforeach

<!-- Delete Modal  -->
<div id="delete-admin-popup" class="modal fade" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<form id="delete-admin-form" class="delete-admin-form" action="{{ route('master_admin.admins') }}" method="post">
				{{ csrf_field() }}
				
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="popup-title" class="modal-title popup-title">Избриши го администраторот</h4>
				</div>
				<div id="delete-admin-body" class="modal-body delete-admin-body">
					<!-- Form content-->
					<h5 id="popup-info" class="popup-info row italic">
						Дали сте сигурни дека сакате да го избришите администраторот?
					</h5>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="post-type" value="delete" />
					<input type="submit" name="delete-admin-popup" class="btn btn-danger" value="Избриши" />
					<button type="button" class="btn btn-default" data-dismiss="modal">Откажи</button>
				</div>
			</form>
		</div>
	</div>
</div>

</section>
<!-- /.content -->

@endsection

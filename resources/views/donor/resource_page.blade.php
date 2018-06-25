@extends('layouts.master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Ресурси
    </h1>
    <ol class="breadcrumb">
      <li><a href="/{{Auth::user()->type()}}/home"><i class="fa fa-dashboard"></i> Почетна</a></li>
      <li><a href="{{route('donor.resource_page')}}"><i class="fa fa-exclamation"></i> Ресурси</a></li>
    </ol>
  </section>



<!-- Main content -->
<section class="content">

  @if (session('status'))
      <div class="alert alert-success">
          {{ session('status') }}
      </div>
  @endif

  <!-- Default box -->
	<div class="edit-resource-page-box resource-page-box box">

		<div class="resource-page-box-body-wrapper">
			<div class="box-body">


				<div id="resource-page-info" class="col-xs-12 resource-page-info">
          {!! $resource->description !!}
				</div>

			</div>
		</div>
		{{-- <div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-resource-page-submit" type="submit" name="edit-resource-page-submit" class="btn btn-success" >Измени</button>
			</div>
		</div> --}}

		<!-- /.box-footer-->
	</div>
  <!-- /.box -->


</section>
<!-- /.content -->

@endsection

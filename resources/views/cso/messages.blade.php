@extends('layouts.master')

@section('content')

  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Почетна
    </h1>
    <ol class="breadcrumb">
      <li><a href="/{{Auth::user()->type()}}/home"><i class="fa fa-dashboard"></i> Почетна</a></li>
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
  <div class="box collapsed-box">
    <div class="box-header with-border">
      <a href="#" class=" btn-box-tool" data-widget="collapse" data-toggle="tooltip" style="display: block;">
      <h3 class="box-title">Title</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
      </div>
    </a>
    </div>
    <div class="box-body">
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      <div class="row">
        <div class="col-xs-12 text-center">
          {{-- <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
            <i class="fa fa-spin fa-refresh"></i>&nbsp; Get External Content
          </button> --}}
        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div>
    {{-- <div class="box-body">
      Pace loading works automatically on page. You can still implement it with ajax requests by adding this js:
      <br/><code>$(document).ajaxStart(function() { Pace.restart(); });</code>
      <br/>
      <div class="row">
        <div class="col-xs-12 text-center">
          <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
            <i class="fa fa-spin fa-refresh"></i>&nbsp; Get External Content
          </button>
        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div> --}}
    <!-- /.box-body -->
    <div class="box-footer">
      Footer
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

  <!-- Default box -->
  <div class="box col-md-6">
    <div class="box-header with-border">
      <a href="#" class=" btn-box-tool" data-widget="collapse" data-toggle="tooltip" style="display: block;">
      <h3 class="box-title">Title</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
      </div>
    </a>
    </div>
    <div class="box-body">
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      <div class="row">
        <div class="col-xs-12 text-center">
          {{-- <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
            <i class="fa fa-spin fa-refresh"></i>&nbsp; Get External Content
          </button> --}}
        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div>
    {{-- <div class="box-body">
      Pace loading works automatically on page. You can still implement it with ajax requests by adding this js:
      <br/><code>$(document).ajaxStart(function() { Pace.restart(); });</code>
      <br/>
      <div class="row">
        <div class="col-xs-12 text-center">
          <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
            <i class="fa fa-spin fa-refresh"></i>&nbsp; Get External Content
          </button>
        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div> --}}
    <!-- /.box-body -->
    <div class="box-footer">
      Footer
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->


  <!-- Default box -->
  <div class="box col-md-6">
    <div class="box-header with-border">
      <a href="#" class=" btn-box-tool" data-widget="collapse" data-toggle="tooltip" style="display: block;">
      <h3 class="box-title">Title</h3>
      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
      </div>
    </a>
    </div>
    <div class="box-body">
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
      <div class="row">
        <div class="col-xs-12 text-center">
          {{-- <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
            <i class="fa fa-spin fa-refresh"></i>&nbsp; Get External Content
          </button> --}}
        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div>
    {{-- <div class="box-body">
      Pace loading works automatically on page. You can still implement it with ajax requests by adding this js:
      <br/><code>$(document).ajaxStart(function() { Pace.restart(); });</code>
      <br/>
      <div class="row">
        <div class="col-xs-12 text-center">
          <button type="button" class="btn btn-default btn-lrg ajax" title="Ajax Request">
            <i class="fa fa-spin fa-refresh"></i>&nbsp; Get External Content
          </button>
        </div>
      </div>
      <div class="ajax-content">
      </div>
    </div> --}}
    <!-- /.box-body -->
    <div class="box-footer">
      Footer
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

</section>
<!-- /.content -->

@endsection

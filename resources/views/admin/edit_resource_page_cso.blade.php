@extends('layouts.admin_master') @section('content')
<!-- Content Header (Page header) -->
<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
<section class="content-header resource-page-content-header">
	<h1>
		<i class="fa fa-user-circle"></i>
		<span>Измени ја страната за известување за примателите</span>
	</h1>
	<ol class="breadcrumb hidden-sm hidden-xs">
		<li>
			<a href="{{route('admin.home')}}"> Admin</a>
		</li>
		<li>
			<a href="{{route('admin.resource_page_cso')}}">
				<i class="fa fa-user-circle"></i> Ресурси</a>
		</li>
	</ol>
</section>


<!-- Main content -->
<section class="content resource-page-content edit-resource-page-content">

	@if (session('status'))
	<div class="alert alert-success">
		{{ session('status') }}
	</div>
	@endif


	<!-- Default box -->
	<div class="edit-resource-page-box resource-page-box box">

	<form id="edit-resource-page-form" class="" action="{{ route('admin.resource_page_cso') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
		<div class="resource-page-box-body-wrapper">
			<div class="box-body">


				<div id="resource-page-info" class="col-xs-12 resource-page-info">

					<!-- Description -->
					<div class="row  form-group{{ ($errors->has('resource-page-description')) ? ' has-error' : '' }}">
						<div class="resource-page-description-value">
							<textarea id="resource-page-description" class="form-control my-editor" name="resource-page-description"
										rows="20" cols="80">{{ (old('resource-page-description')) ? old('resource-page-description') : $resource->description }}</textarea>
							@if ($errors->has('resource-page-description'))
								<span class="help-block">
									<strong>{{ $errors->first('resource-page-description') }}</strong>
								</span>
							@endif
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="box-footer text-center">
			<div class="pull-right">
				<button id="edit-resource-page-submit" type="submit" name="edit-resource-page-submit" class="btn btn-success" >Измени</button>
			</div>
		</div>
	</form>

		<!-- /.box-footer-->
	</div>
	<!-- /.box -->


</section>
<!-- /.content -->


<!-- TinyMCE -->
<script>
  var editor_config = {
    path_absolute : "/",
    selector: "textarea.my-editor",
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
    relative_urls: false,
    file_browser_callback : function(field_name, url, type, win) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
      if (type == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.open({
        file : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no"
      });
    }
  };

  tinymce.init(editor_config);
</script>
@endsection

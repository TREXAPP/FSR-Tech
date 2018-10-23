
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
<!-- CSS -->
<link rel="stylesheet" href="{{asset('css/app.css')}}">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<!-- tableExport -->
 {{-- <script src="//code.jquery.com/jquery.min.js"></script> --}}

{{-- <script>
  $(document).ready(function () {
  $('table').each(function () {
      var $table = $(this);

      var $button = $("<button type='button'>");
      $button.text("Export to spreadsheet");
      $button.insertAfter($table);

      // $button.click(function () {
      //   $('.table-bordered').tableExport({type:'csv'});
      // });
      $button.click(function (e) {
        console.log($table);
        $table.tableExport({type:'pdf',
                           jspdf: {orientation: 'p',
                                   margins: {left:20, top:10},
                                   autotable: false}
                          });
      });
      //$('#downloadPDF').on('click', function (e) { console.log("testing") });
    });
  })
</script> --}}


  <!-- table2csv -->
  {{-- <script src="//code.jquery.com/jquery.min.js"></script>
  <script src="/js/table2csv.js"></script>
  <script>
    $(document).ready(function () {
    $('table').each(function () {
        var $table = $(this);

        var $button = $("<button type='button'>");
        $button.text("Export to spreadsheet");
        $button.insertAfter($table);

        $button.click(function () {
            var csv = $table.table2CSV({
                delivery: 'value'
            });
            window.location.href = 'data:text/csv;charset=UTF-8,'
            + encodeURIComponent(csv);
        });
      });
    })
  </script> --}}


</head>
<body class="hold-transition {{(Auth::user()->type() == 'admin') ? 'skin-blue-light' : 'skin-gray'}} sidebar-mini">
<!-- Site wrapper -->
<div id="app" class="wrapper">
@include('layouts.admin_elements.header')
  <!-- =============================================== -->
@include('layouts.admin_elements.nav-left')
  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


@yield('content')

  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Верзија</b> {{config('app.version')}}
    </div>
    <strong>Copyright &copy; {{date('Y')}} <a href="https://ajdemakedonija.mk">Ајде Македонија</a>.</strong> Сите права се задржани.
  </footer>

{{-- @include('layouts.admin_elements.control-sidebar') --}}

</div>
<!-- ./wrapper -->
<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
<!-- test js, TODO remove when done! -->
<script type="text/javascript" src="{{asset('js/test.js')}}"></script>
<script type="text/javascript" src="/vendor/tableExport/libs/FileSaver/FileSaver.min.js"></script>
<script type="text/javascript" src="/vendor/tableExport/libs/jsPDF/jspdf.min.js"></script>
<script type="text/javascript" src="/vendor/tableExport/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script type="text/javascript" src="/vendor/tableExport/tableExport.min.js"></script>
<script>
  $(document).ready(function () {
    $('table').each(function () {
      var $table = $(this);

      var $button_excel = $("<button type='button'>");
      $button_excel.text("Сними во Ексел");
      $button_excel.insertAfter($table);

      // $button.click(function () {
      //   $('.table-bordered').tableExport({type:'csv'});
      // });
      $button_excel.click(function (e) {
        $table.tableExport({type:'excel'});
      });
      //$('#downloadPDF').on('click', function (e) { console.log("testing") });
    });
  })
</script>
</body>
</html>

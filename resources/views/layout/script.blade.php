<!-- jQuery -->
<script src="{{ url('assets') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="{{ url('assets') }}/plugins/select2/js/select2.full.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{ url('assets') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- InputMask -->
<script src="{{ url('assets') }}/plugins/moment/moment.min.js"></script>
<script src="{{ url('assets') }}/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('assets') }}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('assets') }}/dist/js/demo.js"></script>

@livewireScripts

<script>
  //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    if (localStorage.getItem('dark-mode')) {
      $("#btn-dark-mode").attr("checked", true);
      $("body").addClass("dark-mode");
      $(".main-header").addClass("navbar-dark").removeClass("navbar-light navbar-white");
    }

    $("#btn-dark-mode").on("change", function () {
      if ($(this).is(":checked")) {
        localStorage.setItem('dark-mode', 1);
        $("body").addClass("dark-mode");
        $(".main-header").addClass("navbar-dark").removeClass("navbar-light navbar-white");
      } else {
        localStorage.removeItem('dark-mode');
        $("body").removeClass("dark-mode");
        $(".main-header").addClass("navbar-light navbar-white").removeClass("navbar-dark");
      }
    });
</script>

@stack('custom-script')
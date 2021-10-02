<!-- jQuery -->
<script src="{{ url('assets') }}/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('assets') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="{{ url('assets') }}/plugins/select2/js/select2.full.min.js"></script>
<!-- SweetAlert2 -->
<script src="{{ url('assets') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('assets') }}/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('assets') }}/dist/js/demo.js"></script>

@livewireScripts

<script>
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
</script>

@stack('custom-script')
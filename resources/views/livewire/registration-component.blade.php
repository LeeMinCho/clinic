@section('title')
Registration
@endsection
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Registration</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Registration</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between">
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-registration" data-backdrop="static" wire:click="create()">
                                <i class="fas fa-plus-circle"></i> Add
                            </button>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                    placeholder="Search" wire:model="search">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Patient Name</th>
                                    <th>Queue Number</th>
                                    <th>Queue Status</th>
                                    <th>Registration Number</th>
                                    <th>Registration Status</th>
                                    <th>Paramedic</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registrations as $registration)
                                <tr>
                                    <td>{{ ($registrations->currentPage() - 1) * $registrations->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $registration->patient->full_name }}</td>
                                    <td>{{ $registration->queue_number }}</td>
                                    <td>{{ $registration->queue_status }}</td>
                                    <td>{{ $registration->registration_number }}</td>
                                    <td>{{ $registration->registration_status }}</td>
                                    <td>{{ $registration->paramedic->last_name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#modal-registration" data-backdrop="static"
                                            wire:click="edit({{ $registration->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <i>Total Record: {{ $count_data }} @if ($search)
                                Filtered: {{ $registrations->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $registrations->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-registration" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@if ($isEdit) Edit @else Create @endif @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="clinic_id_selected" id="clinic_id_selected" wire:model="clinic_id">
                    <div class='form-group'>
                        <label for='registration_number'>Registration Number</label>
                        <input type='text' id='registration_number' name='registration_number'
                            class='form-control @if($errors->has("registration_number")) is-invalid @endif' disabled
                            placeholder='Registration Number' wire:model.lazy='registration_number'>
                        @error('registration_number')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='registration_date'>Registration Date</label>
                        <div wire:ignore>
                            <input type='text' id='registration_date' name='registration_date'
                                class='form-control @if($errors->has("registration_date")) is-invalid @endif'
                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                placeholder='Registration Date'>
                        </div>
                        @error('registration_date')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='registration_hour'>Registration Hour</label>
                        <div wire:ignore>
                            <input type='text' id='registration_hour' name='registration_hour'
                                class='form-control @if($errors->has("registration_hour")) is-invalid @endif'
                                data-inputmask-alias="datetime" data-inputmask-inputformat="HH:MM" data-mask
                                placeholder='Registration Hour'>
                        </div>
                        @error('registration_hour')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='patient_id'>Patient</label>
                        <div wire:ignore>
                            <select name="patient_id" id="patient_id" class="form-control select2bs4">
                                <option value="">- Choose Patient -</option>
                            </select>
                        </div>
                        @error('patient_id')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='clinic_id'>Clinic</label>
                        <div wire:ignore>
                            <select name="clinic_id" id="clinic_id" class="form-control select2bs4">
                                <option value="">- Choose Clinic -</option>
                            </select>
                        </div>
                        @error('clinic_id')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='paramedic_id'>Paramedic</label>
                        <div wire:ignore>
                            <select name="paramedic_id" id="paramedic_id" class="form-control select2bs4">
                                <option value="">- Choose Paramedic -</option>
                            </select>
                        </div>
                        @error('paramedic_id')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click.prevent="buttonSave()">
                        Save
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    @push('custom-script')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#patient_id").select2({
            ajax: { 
                url: "{{route('registration.getPatient')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term, // search term,
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#patient_id").on("change", function () {
            @this.set("patient_id", $(this).val());
        });

        $("#clinic_id").select2({
            ajax: { 
                url: "{{route('registration.getClinic')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term, // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#clinic_id").on("change", function () {
            @this.set("clinic_id", $(this).val());
        });
        
        //Datemask dd/mm/yyyy
        $('#registration_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
        $('#registration_hour').inputmask('HH:MM', { 'placeholder': 'HH:MM' });

        $("#registration_date").on("change", function () {
            @this.set("registration_date", $(this).val());
        });
        
        $("#registration_hour").on("change", function () {
            @this.set("registration_hour", $(this).val());
        });

        $( "#paramedic_id" ).select2({
            ajax: { 
                url: "{{route('registration.getParamedic')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term, // search term
                        clinic_id: $("#clinic_id_selected").val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $("#paramedic_id").on("change", function () {
            @this.set("paramedic_id", $(this).val());
        });

        window.livewire.on('btnSave', (message) => {
            jQuery('#modal-registration').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                showConfirmButton: false,
                timer: 2000,
            });
        });
    </script>
    @endpush
</div>
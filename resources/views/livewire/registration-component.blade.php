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
                                    <th>Registration Date</th>
                                    <th>Queue</th>
                                    <th>Registration Number</th>
                                    <th>Patient</th>
                                    <th>Status</th>
                                    <th>Clinic / Paramedic</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($registrations as $registration)
                                <tr>
                                    <td>{{ date('d/m/Y', strtotime($registration->registration_date)) }}
                                    </td>
                                    <td>
                                        {{ $registration->queue_number }} <br>
                                        @if ($registration->queue_status == 0)
                                        <span class="badge bg-red">Cancel</span>
                                        @elseif ($registration->queue_status == 1)
                                        <span class="badge bg-blue">Waiting For Call</span>
                                        @elseif ($registration->queue_status == 2)
                                        <span class="badge bg-blue">Called</span>
                                        @else
                                        <span class="badge bg-green">Done</span>
                                        @endif
                                    </td>
                                    <td>{{ $registration->registration_number }}</td>
                                    <td>{{ $registration->patient->full_name }}</td>
                                    <td>
                                        @if ($registration->registration_status == 0)
                                        <span class="badge bg-red">Cancel</span>
                                        @elseif ($registration->registration_status == 1)
                                        <span class="badge bg-blue">New</span>
                                        @elseif ($registration->registration_status == 2)
                                        <span class="badge bg-blue">Process ...</span>
                                        @else
                                        <span class="badge bg-green">Done</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $registration->clinic->name }} /
                                        {{ $registration->paramedic->first_name . ' ' . $registration->paramedic->last_name }}
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical">
                                            <button type="button" class="btn btn-info" data-toggle="modal"
                                                data-target="#modal-info-registration" data-backdrop="static"
                                                wire:click="$set('idRegistration', {{ $registration->id }})">
                                                <i class="fas fa-info"></i> Info
                                            </button>
                                            <button type="button" class="btn btn-warning" data-toggle="modal"
                                                data-target="#modal-registration" data-backdrop="static"
                                                wire:click="edit({{ $registration->id }})">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </div>
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

    <div class="modal fade" id="modal-info-registration" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Info Registration</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Created By</th>
                            <td>
                                @if ($detail_registration)
                                {{ $detail_registration->userCreated->fullname }} <br>
                                {{ date('d/m/Y H:i', strtotime($detail_registration->created_at)) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>
                                @if ($detail_registration)
                                {{ $detail_registration->userUpdated ? $detail_registration->userUpdated->fullname : "" }}
                                <br>
                                {{ $detail_registration->userUpdated && $detail_registration->updated_at ? date('d/m/Y H:i', strtotime($detail_registration->updated_at)) : "" }}
                                @endif
                            </td>
                        </tr>
                    </table>
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
                    <input type="hidden" name="isEdit" id="isEdit" wire:model="isEdit">
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
            if ($("#isEdit").val() == 'true') {    
                if ($("#clinic_id_selected").val() != $(this).val()) {
                    $("#paramedic_id").val(null).trigger("change");
                }
            }
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

        $("#paramedic_id").select2({
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

        $('#modal-registration').on('shown.bs.modal	', function () {
            if ($("#isEdit").val() == 'true') {
                $("#registration_date").attr("disabled", true);
                $("#registration_hour").attr("disabled", true);
                $("#patient_id").attr("disabled", true);
            } else {
                $("#registration_date").removeAttr("disabled");
                $("#registration_hour").removeAttr("disabled");
                $("#patient_id").removeAttr("disabled");
            }
        });

        window.livewire.on('registration_date', (registration_date) => {
            $("#registration_date").val(registration_date).trigger('change');
        });
        
        window.livewire.on('registration_hour', (registration_hour) => {
            $("#registration_hour").val(registration_hour).trigger('change');
        });
        
        window.livewire.on('patient_id', (patient) => {
            if (patient) {    
                var newOption = new Option(patient.full_name, patient.id, true, true);
                $("#patient_id").append(newOption).trigger("change");
            } else {
                $("#patient_id").val(null).trigger("change");
            }
        });
        
        window.livewire.on('clinic_id', (clinic) => {
            if (clinic) {    
                var newOption = new Option(clinic.name, clinic.id, true, true);
                $("#clinic_id").append(newOption).trigger("change");
            } else {
                $("#clinic_id").val(null).trigger("change");
            }
        });
        
        window.livewire.on('paramedic_id', (paramedic) => {
            if (paramedic) {    
                var newOption = new Option(paramedic.first_name + " " + paramedic.last_name, paramedic.id, true, true);
                $("#paramedic_id").append(newOption).trigger("change");
            } else {
                $("#paramedic_id").val(null).trigger("change");
            }
        });

        window.livewire.on('btnSave', (message) => {
            $('#modal-registration').modal('hide');
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
@section('title')
Patient
@endsection
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Patient</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Patient</li>
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
                                data-target="#modal-patient" data-backdrop="static" wire:click="create()">
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
                                    <th>Medical Number</th>
                                    <th>Full Name</th>
                                    <th>Place Of Birth</th>
                                    <th>Date Of Birth</th>
                                    <th>Address</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Identity Type</th>
                                    <th>Identity Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($patients as $patient)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $patient->medical_number }}</td>
                                    <td>{{ $patient->full_name }}</td>
                                    <td>{{ $patient->place_of_birth }}</td>
                                    <td>{{ $patient->date_of_birth }}</td>
                                    <td>{{ $patient->address }}</td>
                                    <td>{{ $patient->phone_number }}</td>
                                    <td>{{ $patient->email }}</td>
                                    <td>
                                        @if ($patient->identity_type == 1)
                                        ID Card
                                        @elseif ($patient->identity_type == 2)
                                        Driver License
                                        @else
                                        Passport
                                        @endif
                                    </td>
                                    <td>{{ $patient->identity_number }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#modal-patient" data-backdrop="static"
                                            wire:click="edit({{ $patient->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <i>Total Record: {{ $count_data }} @if ($search)
                                Filtered: {{ $patients->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $patients->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-patient" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@if ($isEdit) Edit @else Create @endif @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group'>
                                <label for='medical_number'>Medical Number</label>
                                <input type='text' id='medical_number' name='medical_number'
                                    class='form-control @if($errors->has("medical_number")) is-invalid @endif'
                                    placeholder='Medical Number' wire:model.lazy='medical_number'>
                                @error('medical_number')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='full_name'>Full Name</label>
                                <input type='text' id='full_name' name='full_name'
                                    class='form-control @if($errors->has("full_name")) is-invalid @endif'
                                    placeholder='Full Name' wire:model.lazy='full_name'>
                                @error('full_name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='place_of_birth'>Place Of Birth</label>
                                <input type='text' id='place_of_birth' name='place_of_birth'
                                    class='form-control @if($errors->has("place_of_birth")) is-invalid @endif'
                                    placeholder='Place Of Birth' wire:model.lazy='place_of_birth'>
                                @error('place_of_birth')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='date_of_birth'>Date Of Birth</label>
                                <div @if ($errors->has('date_of_birth')) class="border border-danger rounded" @endif>
                                    <div wire:ignore>
                                        <input type='text' id='date_of_birth' name='date_of_birth' class='form-control'
                                            placeholder='Date Of Birth' data-inputmask-alias="datetime"
                                            data-inputmask-inputformat="dd/mm/yyyy" data-mask>
                                    </div>
                                </div>
                                @error('date_of_birth')
                                <div class='text-danger small'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='phone_number'>Phone Number</label>
                                <input type='text' id='phone_number' name='phone_number'
                                    class='form-control @if($errors->has("phone_number")) is-invalid @endif'
                                    placeholder='Phone Number' wire:model.lazy='phone_number'>
                                @error('phone_number')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group'>
                                <label for='address'>Address</label>
                                <textarea wire:model.lazy='address' name="address" id="address" rows="3"
                                    class="form-control @if($errors->has('address')) is-invalid @endif"
                                    placeholder="Address"></textarea>
                                @error('address')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='email'>Email</label>
                                <input type='text' id='email' name='email'
                                    class='form-control @if($errors->has("email")) is-invalid @endif'
                                    placeholder='Email' wire:model.lazy='email'>
                                @error('email')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='identity_type'>Identity Type</label>
                                <div @if ($errors->has('identity_type')) class="border border-danger rounded" @endif>
                                    <div wire:ignore>
                                        <select name="identity_type" id="identity_type" class="form-control">
                                            <option value="">- Choose Identity Type -</option>
                                            <option value="1">ID Card</option>
                                            <option value="2">Driver License</option>
                                            <option value="3">Passport</option>
                                        </select>
                                    </div>
                                </div>
                                @error('identity_type')
                                <div class='text-danger small'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='identity_number'>Identity Number</label>
                                <input type='text' id='identity_number' name='identity_number'
                                    class='form-control @if($errors->has("identity_number")) is-invalid @endif'
                                    placeholder='Identity Number' wire:model.lazy='identity_number'>
                                @error('identity_number')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
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
        $('#date_of_birth').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })

        $("#date_of_birth").on("change", function () {
            @this.set("date_of_birth", $(this).val());
        });

        window.livewire.on('date_of_birth', (date_of_birth) => {
            $("#date_of_birth").val(date_of_birth).trigger("input");
        });

        $("#identity_type").on("change", function () {
            @this.set("identity_type", $(this).val());
        });

        window.livewire.on('identity_type', (identity_type) => {
            $("#identity_type").val(identity_type).trigger("change");
        });

        window.livewire.on('btnSave', (message) => {
            jQuery('#modal-patient').modal('hide');
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
@section('title')
Paramedic
@endsection
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Paramedic</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Paramedic</li>
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
                                data-target="#modal-paramedic" data-backdrop="static" wire:click="create()">
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
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Paramedic Type</th>
                                    <th>Registration Number</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Identity Type</th>
                                    <th>Identity Number</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($paramedics as $paramedic)
                                <tr>
                                    <td>{{ ($paramedics->currentPage() - 1) * $paramedics->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $paramedic->first_name }}</td>
                                    <td>{{ $paramedic->last_name }}</td>
                                    <td>
                                        @if ($paramedic->paramedic_type == 1)
                                        Doctor
                                        @elseif ($paramedic->paramedic_type == 2)
                                        Nurse
                                        @else
                                        Pharmacist
                                        @endif
                                    </td>
                                    <td>{{ $paramedic->registration_number }}</td>
                                    <td>{{ $paramedic->phone_number }}</td>
                                    <td>{{ $paramedic->address }}</td>
                                    <td>
                                        @if ($paramedic->identity_type == 1)
                                        ID Card
                                        @elseif ($paramedic->identity_type == 2)
                                        Driver License
                                        @else
                                        Passport
                                        @endif
                                    </td>
                                    <td>{{ $paramedic->identity_number }}</td>

                                    <td>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#modal-paramedic" data-backdrop="static"
                                            wire:click="edit({{ $paramedic->id }})">
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
                                Filtered: {{ $paramedics->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $paramedics->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-paramedic" wire:ignore.self>
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
                                <label for='first_name'>First Name</label>
                                <input type='text' id='first_name' name='first_name'
                                    class='form-control @if($errors->has("first_name")) is-invalid @endif'
                                    placeholder='First Name' wire:model.lazy='first_name'>
                                @error('first_name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='last_name'>Last Name</label>
                                <input type='text' id='last_name' name='last_name'
                                    class='form-control @if($errors->has("last_name")) is-invalid @endif'
                                    placeholder='Last Name' wire:model.lazy='last_name'>
                                @error('last_name')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='paramedic_type'>Paramedic Type</label>
                                <div @if ($errors->has('paramedic_type')) class="border border-danger rounded" @endif>
                                    <div wire:ignore>
                                        <select name="paramedic_type" id="paramedic_type" class="form-control">
                                            <option value="">- Choose Paramedic Type -</option>
                                            <option value="1">Doctor</option>
                                            <option value="2">Nurse</option>
                                            <option value="3">Pharmacist</option>
                                        </select>
                                    </div>
                                </div>
                                @error('paramedic_type')
                                <div class='text-danger small'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class='form-group'>
                                <label for='registration_number'>Registration Number</label>
                                <input type='text' id='registration_number' name='registration_number'
                                    class='form-control @if($errors->has("registration_number")) is-invalid @endif'
                                    placeholder='Registration Number' wire:model.lazy='registration_number'>
                                @error('registration_number')
                                <div class='invalid-feedback'>
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
        $("#paramedic_type").on("change", function () {
            @this.set("paramedic_type", $(this).val());
        });

        window.livewire.on('paramedic_type', (paramedic_type) => {
            $("#paramedic_type").val(paramedic_type).trigger("change");
        });
        
        $("#identity_type").on("change", function () {
            @this.set("identity_type", $(this).val());
        });

        window.livewire.on('identity_type', (identity_type) => {
            $("#identity_type").val(identity_type).trigger("change");
        });

        window.livewire.on('btnSave', (message) => {
            jQuery('#modal-paramedic').modal('hide');
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
@section('title')
User
@endsection
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> User</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">User</li>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-user"
                                data-backdrop="static" wire:click="create()">
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
                                    <th>Username</th>
                                    <th>Fullname</th>
                                    <th>Paramedic</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->index + 1 }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->paramedic_id }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning" data-toggle="modal"
                                            data-target="#modal-user" data-backdrop="static"
                                            wire:click="edit({{ $user->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <i>Total Record: {{ $count_data }} @if ($search)
                                Filtered: {{ $users->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $users->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-user" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@if ($isEdit) Edit @else Create @endif @yield('title')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class='form-group'>
                        <label for='username'>Username</label>
                        <input type='text' id='username' name='username'
                            class='form-control @if($errors->has("username")) is-invalid @endif' placeholder='Username'
                            wire:model.lazy='username'>
                        @error('username')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='password'>Password</label>
                        <input type='password' id='password' name='password'
                            class='form-control @if($errors->has("password")) is-invalid @endif' placeholder='Password'
                            @if ($isEdit) disabled @endif wire:model.lazy='password'>
                        @error('password')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='password_confirmation'>Confirm Password</label>
                        <input type='password' id='password_confirmation' name='password_confirmation'
                            class='form-control @if($errors->has("password_confirmation")) is-invalid @endif'
                            placeholder='Confirm Password' @if ($isEdit) disabled @endif
                            wire:model.lazy='password_confirmation'>
                        @error('password_confirmation')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='paramedic_id'>Paramedic</label>
                        <div @if ($errors->has('paramedic_id')) class="border border-danger rounded" @endif>
                            <div wire:ignore>
                                <select name="paramedic_id" id="paramedic_id" class="form-control select2bs4">
                                    <option value="">- Choose Paramedic -</option>
                                </select>
                            </div>
                        </div>
                        @error('paramedic_id')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='fullname'>Fullname</label>
                        <input type='text' id='fullname' name='fullname'
                            class='form-control @if($errors->has("fullname")) is-invalid @endif' placeholder='Fullname'
                            wire:model.lazy='fullname'>
                        @error('fullname')
                        <div class='invalid-feedback'>
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
        $("#paramedic_id").on("change", () => {
            @this.set("paramedic_id", $(this).val());
        });

        window.livewire.on('paramedic_id', (paramedic_id) => {
            $("#paramedic_id").val(paramedic_id).trigger("change");
        });

        window.livewire.on('btnSave', (message) => {
            jQuery('#modal-user').modal('hide');
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
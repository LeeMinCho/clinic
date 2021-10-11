@section('title')
Screen
@endsection
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"> Screen</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Screen</li>
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
                            <button type="button" class="btn btn-primary" @if (auth()->user()->is_admin == 0) disabled
                                @endif data-toggle="modal"
                                data-target="#modal-screen" data-backdrop="static" wire:click="create()">
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
                                    <th>Screen</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th>Flag</th>
                                    <th>Parent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($screens as $screen)
                                <tr>
                                    <td>{{ ($screens->currentPage() - 1) * $screens->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $screen->screen }}</td>
                                    <td>{{ $screen->url }}</td>
                                    <td>
                                        @if ($screen->icon)
                                        <i class="{{ $screen->icon }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($screen->is_menu == 1)
                                        <span class="badge bg-success">Menu</span>
                                        @endif
                                        @if ($screen->is_sub_menu == 1)
                                        <span class="badge bg-success">Sub Menu</span>
                                        @endif
                                    </td>
                                    <td>{{ $screen->parentScreen['screen'] }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning" @if (auth()->user()->is_admin ==
                                            0) disabled @endif data-toggle="modal"
                                            data-target="#modal-screen" data-backdrop="static"
                                            wire:click="edit({{ $screen->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <i>Total Record: {{ $count_data }} @if ($search)
                                Filtered: {{ $screens->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $screens->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-screen" wire:ignore.self>
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
                        <label for='screen'>Screen</label>
                        <input type='text' id='screen' name='screen'
                            class='form-control @if($errors->has("screen")) is-invalid @endif' placeholder='Screen'
                            wire:model.lazy='screen'>
                        @error('screen')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='url'>URL</label>
                        <input type='text' id='url' name='url'
                            class='form-control @if($errors->has("url")) is-invalid @endif' placeholder='Url'
                            wire:model.lazy='url'>
                        @error('url')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='icon'>Icon</label>
                        <input type='text' id='icon' name='icon'
                            class='form-control @if($errors->has("icon")) is-invalid @endif' placeholder='Icon'
                            wire:model.lazy='icon'>
                        @error('icon')
                        <div class='invalid-feedback'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class='form-group'>
                        <label for='screen_id_parent'>Parent</label>
                        <div wire:ignore>
                            <select name="screen_id_parent" id="screen_id_parent" class="form-control select2bs4">
                                <option value="">- Choose Screen -</option>
                            </select>
                        </div>
                        @error('screen_id')
                        <div class='text-danger small'>
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input @if($errors->has('is_menu')) is-invalid @endif"
                                        type="checkbox" value="1" wire:model.lazy="is_menu">
                                    <label class="form-check-label">Menu</label>
                                    @error('is_menu')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input @if($errors->has('is_sub_menu')) is-invalid @endif"
                                        type="checkbox" value="1" wire:model.lazy="is_sub_menu">
                                    <label class="form-check-label">Sub Menu</label>
                                    @error('is_sub_menu')
                                    <div class='invalid-feedback'>
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
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
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $("#screen_id_parent").select2({
            ajax: { 
                url: "{{route('screen.getScreen')}}",
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

        $("#screen_id_parent").on("change", function () {
            @this.set("screen_id", $(this).val());
        });

        window.livewire.on('screen_id', (screen) => {
            if (screen.id) {    
                var newOption = new Option(screen.screen, screen.id, true, true);
                $("#screen_id_parent").append(newOption).trigger("change");
            } else {
                $("#screen_id_parent").val(null).trigger("change");
            }
        });

        window.livewire.on('btnSave', (message) => {
            jQuery('#modal-screen').modal('hide');
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
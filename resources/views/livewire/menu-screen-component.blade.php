<div>
    <div class="modal fade" id="modal-menu-screen" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Screen in {{ $menu_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="menu_id_screen" id="menu_id_screen" wire:model.lazy="menu_id">
                    <div class="row">
                        <div class="col-md-4">
                            <div class='form-group'>
                                <div wire:ignore>
                                    <select name="screen_id" id="screen_id" class="form-control select2bs4">
                                        <option value="">- Choose Screen -</option>
                                    </select>
                                </div>
                                @error('screen_id')
                                <div class='text-danger small'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class='form-group'>
                                <input type="text" class="form-control" name="number_order" id="number_order"
                                    wire:model.lazy="number_order">
                                @error('number_order')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary" wire:click="store()">
                                <i class="fas fa-plus"></i> Add
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Screen</th>
                                    <th>Flag</th>
                                    <th>Order</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($screens as $screen)
                                <tr>
                                    <td>{{ ($screens->currentPage() - 1) * $screens->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $screen->screen }}</td>
                                    <td>
                                        @if ($screen->is_menu == 1)
                                        <span class="badge bg-success">Menu</span>
                                        @endif
                                        @if ($screen->is_sub_menu == 1)
                                        <span class="badge bg-success">Sub Menu</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $screen->menus->first()->pivot->number_order }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger"
                                            onclick="deleteScreen({{ $screen->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5">No Data</td>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        $( "#screen_id" ).select2({
            ajax: { 
                url: "{{route('menu-screen.getScreen')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term, // search term
                        menu_id: $("#menu_id_screen").val()
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

        $("#screen_id").on("change", function () {
            @this.set("screen_id", $(this).val());
        });

        window.livewire.on('screen_id', (screen_id) => {
            $("#screen_id").val(screen_id).trigger("change");
        });
        
        window.livewire.on('showPrimaryModalScreen', () => {
            $("#modal-menu-screen").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });
        
        function deleteScreen(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emit('deleteScreen', id);
                }
            });
        }
    </script>
    @endpush
</div>
<div>
    <div class="modal fade" id="modal-menu-user" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class='form-group'>
                                <div wire:ignore>
                                    <select name="user_id" id="user_id" class="form-control select2bs4">
                                        <option value="">- Choose User -</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('user_id')
                                <div class='text-danger small'>
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
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menu_users as $menu_user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $menu_user->user->fullname }}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger"
                                            wire:click="$emit('deleteUser', {{ $menu_user->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <i>Total Record: {{ $count_data }} @if ($search)
                                Filtered: {{ $menu_users->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $menu_users->links() !!}
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
        $("#user_id").on("change", function () {
            @this.set("user_id", $(this).val());
        });

        window.livewire.on('user_id', (user_id) => {
            $("#user_id").val(user_id).trigger("change");
        });
        
        window.livewire.on('showPrimaryModalUser', (users) => {
            $("#user_id").find("option").not(":first").remove();
            $.each(users, function (i, row) {
                $("#user_id").append("<option value='" + row["id"] + "'>" + row["fullname"] + "</option>");
            });
            $("#modal-menu-user").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });
        
        window.livewire.on('deleteUser', (id) => {
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
                    Livewire.emit('delete', id);
                }
            });
        });
    </script>
    @endpush
</div>
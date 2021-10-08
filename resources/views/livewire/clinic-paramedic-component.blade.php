<div>
    <div class="modal fade" id="modal-clinic-paramedic" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Paramedic in {{ $clinic_name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="clinic_id" id="clinic_id" wire:model="clinic_id">
                    <div class="row justify-content-between">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class='form-group'>
                                        <div wire:ignore>
                                            <select name="paramedic_id" id="paramedic_id"
                                                class="form-control select2bs4">
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
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-primary" wire:click="store()">
                                        <i class="fas fa-plus-circle"></i> Add
                                    </button>
                                </div>
                            </div>
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
                                    <th>Paramedic</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clinic_paramedics as $paramedic)
                                <tr>
                                    <td>{{ ($clinic_paramedics->currentPage() - 1) * $clinic_paramedics->perPage() + $loop->index + 1 }}
                                    </td>
                                    <td>{{ $paramedic->first_name }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger"
                                            onclick="deleteParamedic({{ $paramedic->id }})">
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
                                Filtered: {{ $clinic_paramedics->total() }}
                                @endif</i>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                {!! $clinic_paramedics->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-script')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $( "#paramedic_id" ).select2({
            ajax: { 
                url: "{{route('clinic-paramedic.getParamedic')}}",
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        _token: CSRF_TOKEN,
                        search: params.term, // search term
                        clinic_id: $("#clinic_id").val()
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

        window.livewire.on('paramedic_id', (paramedic_id) => {
            $("#paramedic_id").val(null).trigger("change");
        });

        window.livewire.on('showModal', (clinic_id) => {
            $("#modal-clinic-paramedic").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });

        function deleteParamedic(id) {
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
                    Livewire.emit('deleteParamedic', id);
                }
            });
        }
    </script>
    @endpush
</div>
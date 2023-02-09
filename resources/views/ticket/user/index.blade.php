@extends('layouts.app')

@section('title')
    Ticket List
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="mt-3">
                <table id="datatable" class="table table-bordered dt-responsive table-responsive" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Ticket No</th>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Technician</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('pages-js')
<script type="text/javascript">
    $(document).ready(function () {

        // datatable initialization
        let dataTable = $("#datatable").DataTable({
            ...tableOptions,
            ajax: "{{ route('ticket.index') }}?type=datatable",
            processing: true,
            serverSide : true,
            scrollX: true,
            responsive: false,
            columns: [
                { data: "nomor", name: "nomor", orderable: true  },
                { data: "created_at", name: "created_at", orderable: true  },
                { data: "title", name: "title", orderable: true  },
                { data: "category", name: "category", orderable: true  },
                { data: "status", name: "status", orderable: true  },
                { data: "technician", name: "technician", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        });
        // End datatable initialization


        //Closed Ticket
        const swalWithBootstrapButtonsConfirm = Swal.mixin();
        const swalWithBootstrapButtons = Swal.mixin();

        $(document).on('click', '.closed-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).data('url');
            var label = $(this).data('label');
            var title = $(this).data('title');

            swalWithBootstrapButtonsConfirm.fire({
                title: `Closed ticket number [# ${label} ] ?`,
                text: `Title : [ ${title} ]`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: url,
                        data: {
                            "_method": 'PUT',
                            "_token": "{{ csrf_token() }}",
                        }
                    }).then((data) => {
                        let message = 'Ticket has been closed!';
                        if (data.message) {
                            message = data.message;
                        }
                        swalWithBootstrapButtons.fire('Success!', message, 'success');
                        $('#datatable').DataTable().ajax.reload();
                    }, (data) => {
                        let message = '';
                        if (data.responseJSON.message) {
                            message = data.responseJSON.message;
                        }
                        swalWithBootstrapButtons.fire('Oops!', `Closed ticket not work, ${message}`, 'error');
                        if (data.status === 404) {
                            $('#datatable').DataTable().ajax.reload();
                        }
                    });
                },
                allowOutsideClick: () => !swalWithBootstrapButtons.isLoading(),
                backdrop: true
            });
        });
        //End Closed Ticket
    });   
    

    //Toastr Notification
    @if (session('success')) {
        toastr.success("{{ session('success') }}");
    }
    @endif

    @if (session('error')) {
        toastr.warning("{{ session('error') }}");
    }
    @endif
    //End Toastr Notification
</script>
@endpush
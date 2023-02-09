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
                        <th>Requester</th>
                        <th>Unit</th>
                        <th>Technician</th>
                        <th>Status</th>
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
                { data: "requester", name: "requester", orderable: true  },
                { data: "office", name: "office", orderable: true  },
                { data: "technician", name: "technician", orderable: true  },
                { data: "status", name: "status", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        });
        // End datatable initialization
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
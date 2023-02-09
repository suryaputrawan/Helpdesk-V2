@extends('layouts.app')

@section('title')
    Users List
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="col-xl-12">
                <a href="{{ route('user.create') }}" class="btn btn-soft-primary waves-effect waves-light">Add Data</a>
            </div>

            <div class="mt-3">
                <table id="datatable" class="table table-bordered dt-responsive table-responsive" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
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
            ajax: "{{ route('user.index') }}?type=datatable",
            processing: true,
            serverSide : true,
            scrollX: true,
            responsive: false,
            columns: [
                {
                    data: "id",
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    orderable: false, searchable: false,
                    className: "text-center",
                },
                { data: "name", name: "name", orderable: true  },
                { data: "username", name: "username", orderable: true  },
                { data: "email", name: "email", orderable: true  },
                { data: "role", name: "role", orderable: true  },
                { data: "aktif", name: "aktif", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
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
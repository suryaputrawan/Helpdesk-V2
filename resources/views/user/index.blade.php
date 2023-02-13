@extends('layouts.app')

@section('title')
    Users List
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6">
                    <a href="{{ route('user.create') }}" class="btn btn-soft-primary waves-effect waves-light">Add Data</a>
                </div>
                <div class="col-6 text-end">
                    <button class="btn btn-sm btn-success btn-icon-text" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fe-upload"></i>
                        Import
                    </button>
                </div>
            </div> 
        </div>
        <div class="card-body">
            <div class="mt-3">
                <table id="datatable" class="table table-bordered dt-responsive table-responsive" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Office</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Office Handles</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Data Users</h5>
                </div>
                <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Upload File (.xls / .xlsx)</label>
                                    <input type="file" name="file" id="file-import" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="btn-import">Import</button>
                    </div>
                </form>
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
                { data: "office", name: "office", orderable: true  },
                { data: "role", name: "role", orderable: true  },
                { data: "aktif", name: "aktif", orderable: true  },
                { data: "handle", name: "handle", orderable: true  },
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
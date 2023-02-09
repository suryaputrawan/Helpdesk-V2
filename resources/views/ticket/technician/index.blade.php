@extends('layouts.app')

@section('title')
    Ticket List
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#newTicket" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                            NEW TICKET -
                            <span style="color: blue">{{ $tNew }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#onprogress" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            IN PROGRESS TICKET - 
                            <span style="color: blue">{{ $tProgress }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#hold" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            ON HOLD TICKET - 
                            <span style="color: blue">{{ $tHold }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#solved" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            SOLVED TICKET - 
                            <span style="color: blue">{{ $tSolved }}</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="newTicket">
                        <div class="table-responsive">
                            <table id="dt-new" class="table table-bordered dt-responsive" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Ticket No</th>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Requester</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> 
                    
                    <div class="tab-pane" id="onprogress">
                        <div class="table-responsive">
                            <table id="dt-progress" class="table table-bordered dt-responsive" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Ticket No</th>
                                        <th>Date</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Requester</th>
                                        <th>Unit</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div> 
    
                    <div class="tab-pane" id="hold">
                        <table id="dt-hold" class="table table-bordered dt-responsive table-responsive" width="100%">
                            <thead>
                            <tr>
                                <th>Ticket No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Requester</th>
                                <th>Unit</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
    
                    <div class="tab-pane" id="solved">
                        <table id="dt-solved" class="table table-bordered dt-responsive table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Requester</th>
                                    <th>Unit</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pages-js')
<script type="text/javascript">
    //Initialization Datatable
    $(document).ready(function () {
        // New Ticket
        $("#dt-new").DataTable({
            ...tableOptions,
            ajax: "{{ route('ticket.index') }}?type=datatable",
            processing: true,
            serverSide : true,
            scroller: true,
            responsive: false,
            columns: [
                { data: "nomor", name: "nomor", orderable: true  },
                { data: "created_at", name: "created_at", orderable: true  },
                { data: "title", name: "title", orderable: true  },
                { data: "category", name: "category", orderable: true  },
                { data: "requester", name: "requester", orderable: true  },
                { data: "office", name: "office", orderable: true  },
                { data: "status", name: "status", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        })
        // End New Ticket

        // In Progress Ticket
        $("#dt-progress").DataTable({
            ...tableOptions,
            ajax: "{{ route('ticket.progress') }}?type=datatable",
            processing: true,
            serverSide : true,
            scroller: true,
            responsive: false,
            columns: [
                { data: "nomor", name: "nomor", orderable: true  },
                { data: "created_at", name: "created_at", orderable: true  },
                { data: "title", name: "title", orderable: true  },
                { data: "category", name: "category", orderable: true  },
                { data: "requester", name: "requester", orderable: true  },
                { data: "office", name: "office", orderable: true  },
                { data: "status", name: "status", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        });
        // End In Progress Ticket

        // Hold Ticket
        $("#dt-hold").DataTable({
            ...tableOptions,
            ajax: "{{ route('ticket.hold') }}?type=datatable",
            processing: true,
            serverSide : true,
            scroller: true,
            responsive: false,
            columns: [
                { data: "nomor", name: "nomor", orderable: true  },
                { data: "created_at", name: "created_at", orderable: true  },
                { data: "title", name: "title", orderable: true  },
                { data: "category", name: "category", orderable: true  },
                { data: "requester", name: "requester", orderable: true  },
                { data: "office", name: "office", orderable: true  },
                { data: "status", name: "status", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        });
        // End Hold Ticket

        // Solved Ticket
        $("#dt-solved").DataTable({
            ...tableOptions,
            ajax: "{{ route('ticket.solved') }}?type=datatable",
            processing: true,
            serverSide : true,
            scroller: true,
            responsive: false,
            columns: [
                { data: "nomor", name: "nomor", orderable: true  },
                { data: "created_at", name: "created_at", orderable: true  },
                { data: "title", name: "title", orderable: true  },
                { data: "category", name: "category", orderable: true  },
                { data: "requester", name: "requester", orderable: true  },
                { data: "office", name: "office", orderable: true  },
                { data: "status", name: "status", orderable: true  },
                { data: "action", name: "action", orderable: false, searchable: false, className: "text-center", },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        });
        // End Solved Ticket

        //Fix Incorrect column widths or missing data
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                // .scroller.measure();
                .columns.adjust()
                .fixedColumns().relayout();
        });
    });
    //// End datatable initialization

    //Assign ticket
    $(document).ready(function() {
        const swalWithBootstrapButtonsConfirm = Swal.mixin();
        const swalWithBootstrapButtons = Swal.mixin();

        $(document).on('click', '.assign-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).data('url');
            var label = $(this).data('label');

            swalWithBootstrapButtonsConfirm.fire({
                title: `Assign ticket number [# ${label} ] ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Assign',
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
                        let message = 'Ticket has been assign';
                        if (data.message) {
                            message = data.message;
                        }
                        swalWithBootstrapButtons.fire('Success!', message, 'success');
                        $('#dt-new').DataTable().ajax.reload();
                        $('#dt-progress').DataTable().ajax.reload();
                    }, (data) => {
                        let message = '';
                        if (data.responseJSON.message) {
                            message = data.responseJSON.message;
                        }
                        swalWithBootstrapButtons.fire('Oops!', `Assign not work, ${message}`, 'error');
                        if (data.status === 404) {
                            $('#dt-new').DataTable().ajax.reload();
                        }
                    });
                },
                allowOutsideClick: () => !swalWithBootstrapButtons.isLoading(),
                backdrop: true
            });
        });
    });
    //End Assign

    //Toastr Notification
    @if (session('success')) {
        toastr.success("{{ session('success') }}");
    }
    @endif

    @if (session('error')) {
        Swal.fire({
            title: `INFO`,
            text: "{{ session('error') }}",
            icon: 'warning',
            backdrop: true
        });
    }
    @endif
    //End Toastr
</script>
@endpush
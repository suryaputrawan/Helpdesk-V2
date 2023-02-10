@extends('layouts.app')

@section('title')
    Report
@endsection

@section('content')
    <div class="card">
        <div class="card-header text-end">
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="fe-filter"></i>
            </button>
            <button class="btn btn-sm btn-success btn-icon-text" type="button" data-bs-toggle="modal" data-bs-target="#exportModal">
                <i class="fe-download"></i>
                Export
            </button>
        </div>
        <div class="card-body">
            <div class="collapse mb-2" id="collapseExample">
                <form>
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date_filter" id="start-date-filter" class="form-control">
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date_filter" id="end-date-filter" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="mb-2">
                                <label class="form-label">Office</label>
                                <select name="office_filter" id="office-filter" class="js-example-basic-single form-control" data-toggle="select2" data-width="100%">
                                    <option selected disabled>All Office</option>
                                    @foreach ($office as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-2">
                                <label class="form-label">Category</label>
                                <select name="category_filter" id="category-filter" class="js-example-basic-single form-control" data-width="100%" data-toggle="select2">
                                    <option selected disabled>All Categories</option>
                                    @foreach ($category as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="mb-2">
                                <label class="form-label">Status Ticket</label>
                                <select name="status_filter" id="status-filter" class="js-example-basic-single form-control" data-width="100%" data-toggle="select2">
                                    <option selected disabled>All Status</option>
                                    @foreach ($status as $data)
                                    <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 mb-2" style="display: flex; gap: 8px">
                            <button type="button" id="btn-reset" class="btn btn-secondary" style="align-self: flex-end">
                                Reset
                            </button>
                            <button type="button" id="btn-filter" class="btn btn-primary" style="align-self: flex-end">
                                Filter Data
                            </button>
                        </div>
                    </div>
                </form>
                <hr>
            </div>

            <div class="table-responsive">
                <table id="datatable" class="table table-bordered dt-responsive" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Requester</th>
                        <th>Location</th>
                        <th>Office</th>
                        <th>Assign Date</th>
                        <th>Solved Date</th>
                        <th>Duration</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                </table>
            </div> 
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Export Data Ticket</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
            <div class="row">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date_export" id="start-date-export" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date_export" id="end-date-export" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="mb-2">
                        <label class="form-label">Office</label>
                        <select name="office_export" id="office-export" class="js-example-basic-single form-select" data-width="100%">
                            <option selected disabled>All Office</option>
                            @foreach ($office as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
    
                <div class="col-xs-12">
                    <div class="mb-2">
                        <label class="form-label">Category</label>
                        <select name="category_export" id="category-export" class="js-example-basic-single form-select" data-width="100%">
                            <option selected disabled>All Categories</option>
                            @foreach ($category as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="mb-2">
                        <label class="form-label">Status Ticket</label>
                        <select name="status_export" id="status-export" class="js-example-basic-single form-select" data-width="100%">
                            <option selected disabled>All Status</option>
                            @foreach ($status as $data)
                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-danger" id="btn-export-pdf">Export PDF</button>
            <button type="button" class="btn btn-success" id="btn-export-xls">Export XLS</button>
            </div>
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
            ajax: {
                url: "{{ route('report.index') }}?type=datatable",
                data: function(d) {
                    d.office_id = Number($('select[name=office_filter] option:selected').val())
                    d.category_id = Number($('select[name=category_filter] option:selected').val())
                    d.status_id = Number($('select[name=status_filter] option:selected').val())
                    d.start_date = $('input[name=start_date_filter]').val()
                    d.end_date = $('input[name=end_date_filter]').val()
                }
            },
            processing: true,
            serverSide : true,
            scroller: true,
            responsive: false,
            columns: [
                { data: "nomor", name: "nomor", orderable: true  },
                { data: "date", name: "date", orderable: true  },
                { data: "title", name: "title", orderable: true  },
                { data: "category", name: "category", orderable: true  },
                { data: "requester", name: "requester", orderable: true  },
                { data: "location", name: "location", orderable: true  },
                { data: "office", name: "office", orderable: true  },
                { data: "assign_date", name: "assign_date", orderable: true  },
                { data: "solved_date", name: "solved_date", orderable: true  },
                { data: "interval", name: "interval", orderable: true  },
                { data: "status", name: "status", orderable: true  },
            ],
            order: [[0, 'desc']],
            drawCallback: function( settings ) {
                feather.replace()
            }
        });
        // End datatable initialization

        var exportModal = document.getElementById('exportModal')
        exportModal.addEventListener('show.bs.modal', function (event) {
            $("#office-export").prop('selectedIndex', 0).change();
            $("#category-export").prop('selectedIndex', 0).change();
            $("#status-export").prop('selectedIndex', 0).change();
            $('#start-date-export').val('').change();
            $('#end-date-export').val('').change();
        });

        $('#btn-export-pdf').on('click', function() {
            var office = Number($('select[name=office_export] option:selected').val());
            var category = Number($('select[name=category_export] option:selected').val());
            var status = Number($('select[name=status_export] option:selected').val());
            var start_date = $('input[name=start_date_export]').val();
            var end_date = $('input[name=end_date_export]').val();
            var url = `{{ route('report.ticket.exportPdf') }}?start_date=${start_date}&end_date=${end_date}&office=${office}&category=${category}&status=${status}`;
            window.open(url, '_blank').focus();
        });

        //Filter Data
        $('#btn-filter').on('click', function() {
            dataTable.ajax.reload();
        })

        //Reset Data
        $('#btn-reset').on('click', function(){
            $("#office-filter").prop('selectedIndex', 0).change();
            $("#category-filter").prop('selectedIndex', 0).change();
            $("#status-filter").prop('selectedIndex', 0).change();
            $('#start-date-filter').val('').change();
            $('#end-date-filter').val('').change();
            dataTable.ajax.reload();
        });
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
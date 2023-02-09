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
                            NEW TICKET <span style="color: red">({{ count($tNewTicket) }})</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#onprogress" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            IN PROGRESS TICKET <span style="color: red">({{ count($tOnProgress) }})</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#hold" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            ON HOLD TICKET <span style="color: red">({{ count($tOnHold) }})</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#solved" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                            SOlVED TICKET <span style="color: red">({{ count($tSolved) }})</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane show active" id="newTicket">
                        <table id="selection-datatable" class="table table-bordered wrap">
                            <thead>
                            <tr>
                                <th>Ticket No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Requester</th>
                                <th>Unit</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tNewTicket as $index => $data)
                                    <tr>
                                        <td>#{{ $data->nomor }} </td>
                                        <td>{{ $data->created_at->format("d M Y") }} </td>
                                        <td>{{ $data->title }} </td>
                                        <td>{{ $data->category->name }} </td>
                                        <td>{{ $data->status->name }} </td>
                                        <td>{{ $data->userRequester->name}} </td>
                                        <td>{{ $data->userRequester->office->name}} </td>
                                        <td>
                                            <div class="d-flex">
                                                @if ($data->assign == 0)
                                                <a type="button" class="assign-btn btn btn-soft-danger waves-effect waves-light me-1"
                                                    data-id="{{ $data->id }}"
                                                    data-nomor="{{ $data->nomor }}"
                                                    data-url="{{ route('ticket.assign', Crypt::encryptString($data->id)) }}">
                                                    Assign
                                                </a>
                                                @else
                                                    <a href="{{ route('ticket.show', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">View</a>
                                                @endif
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 
                    
                    <div class="tab-pane" id="onprogress">
                        <table id="datatable" class="table table-bordered wrap">
                            <thead>
                            <tr>
                                <th>Ticket No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Requester</th>
                                <th>Unit</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tOnProgress as $index => $data)
                                    <tr>
                                        <td>#{{ $data->nomor }} </td>
                                        <td>{{ $data->created_at->format("d M Y") }} </td>
                                        <td>{{ $data->title }} </td>
                                        <td>{{ $data->category->name }} </td>
                                        <td>{{ $data->status->name }} </td>
                                        <td>{{ $data->userRequester->name}} </td>
                                        <td>{{ $data->userRequester->office->name}} </td>
                                        <td>
                                            <div class="d-flex">
                                                {{-- <a href="{{ route('ticket.edit', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">Edit</a> --}}
                                                <a href="{{ route('ticket.show', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">View</a>
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> 
    
                    <div class="tab-pane" id="hold">
                        <table id="key-table" class="table table-bordered wrap">
                            <thead>
                            <tr>
                                <th>Ticket No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Requester</th>
                                <th>Unit</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tOnHold as $index => $data)
                                    <tr>
                                        <td>#{{ $data->nomor }} </td>
                                        <td>{{ $data->created_at->format("d M Y") }} </td>
                                        <td>{{ $data->title }} </td>
                                        <td>{{ $data->category->name }} </td>
                                        <td>{{ $data->status->name }} </td>
                                        <td>{{ $data->userRequester->name}} </td>
                                        <td>{{ $data->userRequester->office->name}} </td>
                                        <td>
                                            <div class="d-flex">
                                                {{-- <a href="{{ route('ticket.edit', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">Edit</a> --}}
                                                <a href="{{ route('ticket.show', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">View</a>
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
    
                    <div class="tab-pane" id="solved">
                        <table id="responsive-datatable" class="table table-bordered wrap">
                            <thead>
                            <tr>
                                <th>Ticket No</th>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Requester</th>
                                <th>Unit</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach ($tSolved as $index => $data)
                                    <tr>
                                        <td>#{{ $data->nomor }} </td>
                                        <td>{{ $data->created_at->format("d M Y") }} </td>
                                        <td>{{ $data->title }} </td>
                                        <td>{{ $data->category->name }} </td>
                                        <td>{{ $data->status->name }} </td>
                                        <td>{{ $data->userRequester->name}} </td>
                                        <td>{{ $data->userRequester->office->name}} </td>
                                        <td>
                                            <div class="d-flex">
                                                {{-- <a href="{{ route('ticket.edit', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">Edit</a> --}}
                                                <a href="{{ route('ticket.show', Crypt::encryptString($data->id)) }}" class="btn btn-soft-primary waves-effect waves-light me-1">View</a>
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pages-js')

<script>
    //Toastr Notification
    @if (session('success')) {
        toastr.success("{{ session('success') }}");
    }
    @endif

    @if (session('error')) {
        toastr.warning("{{ session('error') }}");
    }
    @endif
    //End Toastr

    //Assign ticket
    $(document).ready(function() {
        const swalWithBootstrapButtonsConfirm = Swal.mixin();
        const swalWithBootstrapButtons = Swal.mixin();

        $(document).on('click', '.assign-btn', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var url = $(this).data('url');
            var nomor = $(this).data('nomor');

            swalWithBootstrapButtonsConfirm.fire({
                title: `Assign ticket number [# ${nomor} ] ?`,
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
                        location.reload()
                    }, (data) => {
                        let message = '';
                        if (data.responseJSON.message) {
                            message = data.responseJSON.message;
                        }
                        swalWithBootstrapButtons.fire('Oops!', `Gagal menghapus data, ${message}`, 'error');
                        if (data.status === 404) {
                            location.reload()
                        }
                    });
                },
                allowOutsideClick: () => !swalWithBootstrapButtons.isLoading(),
                backdrop: true
            });
        });
    });
    //End Assign
</script>
@endpush
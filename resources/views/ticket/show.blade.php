@extends('layouts.app')

@section('title')
    Ticket Details
@endsection

@section('button-back')
    <div class="col-2">
        <div class="text-end mt-3">
            <a href="{{ route('ticket.index') }}" class="btn btn-secondary waves-effect">Back</a>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body task-detail">
                    <div class="d-flex mb-3">
                        {{-- <img class="flex-shrink-0 me-3 rounded-circle avatar-md" alt="64x64" src="{{ asset('assets/images/users/user-2.jpg') }}"> --}}
                        <div class="flex-grow-2">
                            <h3 class="media-heading mt-0">{{ $ticket->userRequester->name }}</h3>
                            <h4>
                                @if ($ticket->status->name == "Solved" || $ticket->status->name == 'Closed')
                                    <span class="badge bg-danger">Status : {{ $ticket->status->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Status : {{ $ticket->status->name }}</span>
                                @endif
                                <span class="badge bg-primary">Category : {{ $ticket->category->name }}</span>
                            </h4>
                        </div>
                    </div>

                    <h4>Ticket ID : #{{ $ticket->nomor }}</h4>

                    <h5 class="mt-4">Title : {{ $ticket->title }}</h5>

                    <p class="text-muted">
                        {{ $ticket->detail_trouble }}
                    </p>

                    <div class="row task-dates mb-0 mt-2">
                        <div class="col-lg-5">
                            <h5 class="font-600 m-b-5">Location</h5>
                            <p> {{ $ticket->location->name }} 
                        </div>
                        <div class="col-lg-5">
                            <h5 class="font-600 m-b-5">Office</h5>
                            <p> {{ $ticket->userRequester->office->name }}
                        </div>
                    </div>

                    <div class="row task-dates mb-0">
                        <?php
                            $dateTime = strtotime($ticket->assign_date);
                            $date = date('d M Y', $dateTime);
                            $time = date('H:i:s', $dateTime);
                        ?>
                        <div class="col-lg-5">
                            <h5 class="font-600 m-b-5">Assign Date</h5>
                            @if ($ticket->assign_date != null)
                                <p style="color: blue"> {{ $date }} - {{ $time }} 
                            @else
                                <p>Technician has not been assigned</p>
                            @endif
                            
                        </div>
                        <div class="col-lg-5">
                            <h5 class="font-600 m-b-5">Solved Date</h5>
                            @if ($resolution->status->name == "Solved")
                            <?php
                                $dateTime = strtotime($resolution->date);
                                $date = date('d M Y', $dateTime);
                                $time = date('H:i:s', $dateTime);
                            ?>
                                <p style="color: red"> {{ $date }} - {{ $time }}
                            @else
                                <p> {{ $ticket->status->name }}
                            @endif
                        </div>
                        <div class="col-lg-5">
                            <h5 class="font-600 m-b-5">Work Duration</h5>
                            @if ($resolution->status->name == "Solved")
                                <p style="color: red"> {{ $ticketTime }}
                            @else
                                <p> {{ $ticket->status->name }}
                            @endif
                        </div>
                    </div>

                    <div class="assign-team mt-2">
                        <h5>Technician</h5>
                        <h3>
                            <span class="badge badge-outline-success">{{ $ticket->userTechnician != null ? $ticket->userTechnician->name : 'Technician has not been assigned' }}</span>
                        </h3>
                    </div>

                    <div class="assign-team mt-3">
                        <h5>Resolution</h5>
                        <table class="table">
                            <tr>
                                <td>{{ $resolution != null ? $resolution->description : ''  }}</td>
                            </tr>
                            <tr>
                                <td>
                                    @if ($ticket)
                                    Item : 
                                        @foreach ($ticket->item as $data)
                                            <li>{{ $data->name }} ({{ $data->pivot->description }})</li>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>    
        </div><!-- end col -->

    <!--Progress card-->
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Ticket Progress ({{ count($progress) }})</h4>
                <hr>
                <div>
                    @foreach ($progress as $index => $data)
                    <?php
                        $dateTime = strtotime($data->date);
                        $date = date('d M Y', $dateTime);
                        $time = date('H:i:s', $dateTime);
                    ?>
                        @if ($data->user->role == "User" || $data->user->role == "Management" )
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h5 class="mt-0 mb-0">{{ $data->user->name }}</h5>
                                    <p class="font-13 text-muted mb-2 mt-0">
                                        {{ $date }} - {{ $time }}
                                    </p>
                                    <p class="font-13 mb-1">
                                        {{ $data->description }}
                                    </p>
                                    @if ($data->status->name == 'Solved' || $data->status->name == 'Closed')
                                        <span class="badge bg-danger">{{ $data->status->name }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ $data->status->name }}</span>
                                    @endif 
                                </div>
                            </div>
                            <hr>
                        @else
                            <div class="d-flex text-end">
                                <div class="flex-grow-1">
                                    <h5 class="mt-0 mb-0">{{ $data->user->name }}</h5>
                                    <p class="font-13 text-muted mb-2 mt-0">
                                        {{ $date }} - {{ $time }}
                                    </p>
                                    <p class="font-13 mb-1">
                                        {{ $data->description }}
                                    </p>
                                    @if ($data->status->name == 'Solved' || $data->status->name == 'Closed')
                                        <span class="badge bg-danger">{{ $data->status->name }}</span>
                                    @else
                                        <span class="badge bg-primary">{{ $data->status->name }}</span>
                                    @endif 
                                </div>
                            </div>
                            <hr>
                        @endif     
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pages-js')
<script type="text/javascript">
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
@extends('layouts.app')

@section('title')
    Update Progress Ticket
@endsection

@section('button-back')
    <div class="col-6">
        <div class="text-end mt-3">
            <a href="{{ route('ticket.index') }}" class="btn btn-secondary waves-effect">Back</a>
        </div>
    </div>  
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body task-detail">
                <table id="tb-ticket" class="table table-bordered dt-responsive table-responsive">
                    <tr>
                        <th>Requester</th>
                        <td>{{ $ticket->userRequester->name }}</td>
                        <th>Ticket Status</th>
                        @if ($ticket->status->name == 'Solved')
                            <td style="color: red">{{ $ticket->status->name }}</td>
                        @else
                            <td style="color: blue">{{ $ticket->status->name }}</td>
                        @endif
                        
                    </tr>
                    <tr>
                        <th>Ticket Number</th>
                        <td>{{ $ticket->nomor }}</td>
                        <th>Category</th>
                        <td>{{ $ticket->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{{ $ticket->location->name }}</td>
                        <th>Date Request</th>
                        <td>{{ $ticket->created_at->format("d M Y") }}</td>
                        </tr>
                        <tr>
                        <th>Office</th>
                        <td>{{ $ticket->office->name }}</td>
                        <th>Date Solved</th>
                        @if ($resolution->status->name == 'Solved')
                        <?php
                            $dateTime = strtotime($resolution->date);
                            $date = date('d M Y', $dateTime);
                            $time = date('H:i:s', $dateTime);
                        ?>
                            <td>{{ $date }} - {{ $time }}</td>
                        @else
                            <td></td>
                        @endif
                        </tr>
                </table>

                <h5>Trouble Request</h5>

                <table id="tb-trouble" class="table table-bordered dt-responsive table-responsive">
                    <tr>
                        <th>Title</th>
                        <td>{{ $ticket->title }}</td>
                    </tr>
                    <tr>
                        <th>Detail</th>
                        <td>{{ $ticket->detail_trouble }}</td>
                    </tr>
                </table>

                <h5>Resolution</h5>

                <table id="tb-resolution" class="table table-bordered dt-responsive table-responsive">
                    <tr>
                        <th>Technician</th>
                        <td>{{ $resolution != null ? $resolution->description : ''  }}</td>
                    </tr>
                    <tr>
                        <th>Item Perbaikan</th>
                        <td>
                            @if ($ticket)
                                @foreach ($ticket->item as $data)
                                    <li>{{ $data->name }} ({{ $data->pivot->description }})</li>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                @if (auth()->user()->role == 'Technician' && $ticket->status->name != 'Solved')
                    <div class="mt-4">
                        <h4 class="header-title">Update Progress Ticket</h4>
                        <p class="sub-header">Isikan dengan lengkap data berikut</p>
                                    
                        <form action="{{ route('ticket.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status_id" id="status_id" class="form-control @error('status_id') is-invalid @enderror" data-toggle="select2">
                                            @foreach ($status as $data)
                                                <option value="{{ $data->id }}" {{ old('status_id', $ticket->status_id) == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('status_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">                     
                                        <label for="status" class="form-label">Items <span class="text-danger">(Tambahkan item yang dilakukan penggantian atau perbaikan, jika ada!)</span></label>
                                </div>
                                <div class="col-lg-4">
                                    <div class="text-end mb-1">
                                        <button id="btn-item-add" type="button" class="btn btn-sm btn-primary waves-effect waves-light">Tambah Item</button>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="item-form">
                                <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <table id="tb-item" class="table table-bordered table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (old('keterangan') || old('item_id'))
                                                @for ($i = 0; $i < count(old('item_id')); $i++)
                                                <tr>
                                                    <td>
                                                        <select name="item_id[]" id="item_id" class="form-select @error('item_id.'.$i) is-invalid @enderror">
                                                            <option value="">Please Selected</option>
                                                            @foreach ($items as $data)
                                                                <option value="{{ $data->id }}" {{ old('item_id.'.$i) == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error('item_id.'.$i)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control @error('keterangan.'.$i) is-invalid @enderror" name="keterangan[]" id="keterangan" placeholder="Contoh: RAM 8GB PC-2500" value="{{ old('keterangan.'.$i) }}" autocomplete="off">
                                                        @error('keterangan.'.$i)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <button id="btn-item-delete" type="button" class="btn btn-danger waves-effect waves-light"><i class='mdi mdi-delete'></i></button>
                                                    </td>
                                                </tr>
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Update Progress Pengerjaan <span class="text-danger">*</span></label>
                                <div>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="6" autocomplete="off" placeholder="Masukkan update progress pengerjaan dari request ticket">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                
                            <div class="text-end">
                                <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
                                <a href="{{ route('ticket.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('pages-js')
<script type="text/javascript">

    let item = "<tr>"+
                    "<td>"+
                        "<select name='item_id[]' id='item_id' class='form-select'>"+
                            "<option value=''>Please Selected</option>"+
                            "@foreach ($items as $data)"+
                                "<option value='{{ $data->id }}' {{ old('item_id.*') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>"+
                            "@endforeach"+
                        "</select>"+
                    "</td>"+
                    "<td>"+
                        "<input type='text' class='form-control' name='keterangan[]' placeholder='Contoh: RAM 8GB PC-2500' value='{{ old('keterangan.*') }}' autocomplete='off'>"+
                    "</td>"+
                    "<td>"+
                        "<button id='btn-item-delete' type='button' class='btn btn-danger waves-effect waves-light'><i class='mdi mdi-delete'></i></button>"+
                    "</td>"+
                "</tr>"
    //--Repeat item form
    $(document).ready(function() {
        $('#btn-item-add').click(function() {
            $('#tb-item > tbody').append(item);
        });

        $('tbody').on('click','#btn-item-delete', function() {
            $(this).parent().parent().remove();
        });
    });
    //--- End repeat item form

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
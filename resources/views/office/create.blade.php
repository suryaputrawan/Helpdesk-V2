@extends('layouts.app')

@section('title')
    Create Office
@endsection

@section('content')
<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('office.store') }}" method="POST" enctype="multipart/form-data" class="parsley-examples" novalidate="">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Office Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" parsley-trigger="change" placeholder="Enter office name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Save</button>
                    <a href="{{ route('office.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('pages-js')
<!--Notification-->
<script>
    @if (session('error')) {
        Swal.fire({
            title: `INFO`,
            text: "{{ session('error') }}",
            icon: 'warning',
            backdrop: true
        });
    }
    @endif
</script>
@endpush
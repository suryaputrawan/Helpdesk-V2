@extends('layouts.app')

@section('title')
    Edit Department
@endsection

@section('content')
<div class="col-lg-6">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('department.update', $department->id) }}" method="POST" class="parsley-examples" novalidate="">
                @csrf
                @method("patch")
                <div class="mb-3">
                    <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" parsley-trigger="change" required="" placeholder="Input Department Name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ $department->name }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
                    <a href="{{ route('department.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
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
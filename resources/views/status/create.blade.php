@extends('layouts.app')

@section('title')
    Create Status
@endsection

@section('content')
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning d-none fade show">
                    <h4 class="mt-0 text-warning">Oh snap!</h4>
                    <p class="mb-0">This form seems to be invalid :(</p>
                </div>
        
                <div class="alert alert-info d-none fade show">
                    <h4 class="mt-0 text-info">Yay!</h4>
                    <p class="mb-0">Everything seems to be ok :)</p>
                </div>
        
                <form action="{{ route('status.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3" >
                        <label for="name" class="form-label">Status Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="fullname" placeholder="Input Status Name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
        
                    <div class="text-end">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Save</button>
                        <a href="{{ route('status.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
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

@extends('layouts.app')

@section('title')
    Edit Data Location
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
        
                <form action="{{ route('location.update', $location->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="mb-3" >
                        <label for="name" class="form-label">Location Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="fullname" placeholder="Input Status Name" value="{{ old('name', $location->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="office" class="form-label">Office <span class="text-danger">*</span></label>
                        <select name="office_id" id="office" class="form-control @error('office_id') is-invalid @enderror" data-toggle="select2">
                            <option selected disabled>Please select</option>
                            @foreach ($office as $data)
                              <option value="{{ $data->id }}" {{ old('office_id', $location->office_id) == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                            @endforeach
                          </select>
                          @error('office_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                    </div>
        
                    <div class="text-end">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
                        <a href="{{ route('location.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
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

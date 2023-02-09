@extends('layouts.app')

@section('title')
    Create Tickets
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">           
                    <form action="{{ route('ticket.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" placeholder="Contoh: Komputer Error" value="{{ old('title') }}">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category" class="form-control @error('category_id') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        @foreach ($category as $data)
                                          <option value="{{ $data->id }}" {{ old('category_id') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <select name="location_id" id="location" class="form-control @error('location_id') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        @foreach ($location as $data)
                                          <option value="{{ $data->id }}" {{ old('location_id') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('location_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="detail_trouble" class="form-label">Detail Trouble <span class="text-danger">*</span></label>
                            <div>
                                <textarea name="detail_trouble" id="detail_trouble" class="form-control @error('detail_trouble') is-invalid @enderror" rows="6" autocomplete="off" placeholder="Masukkan detail trouble yang terjadi">{{ old('detail_trouble') }}</textarea>
                                @error('detail_trouble')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
            
                        <div class="text-end">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">Save</button>
                            <a href="{{ route('ticket.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
                        </div>
                    </form>
                </div>
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

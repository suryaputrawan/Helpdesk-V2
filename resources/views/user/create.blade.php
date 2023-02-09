@extends('layouts.app')

@section('title')
    Create User
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
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
            
                    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="fullname" class="form-label">Full Name <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="fullname" value="{{ old('name') }}" placeholder="Example: Handoko Budi Santosa Purnomo">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="username" class="form-label">Username <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" value="{{ old('username') }}" placeholder="NIK Karyawan. Example: 12345678910">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" data-parsley-trigger="change" value="{{ old('email') }}" placeholder="Example: user@gmail.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span style="color: red">*</span></label>
                                    <input type="password" id="password" class="form-control" name="password" data-parsley-trigger="change" required="" placeholder="Password minimal 6 ">
                                </div> 
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="office" class="form-label">Office <span style="color: red">*</span></label>
                                    <select name="office_id" id="office" class="form-control @error('office_id') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        @foreach ($office as $data)
                                          <option value="{{ $data->id }}" {{ old('office_id') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('office_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department <span style="color: red">*</span></label>
                                    <select name="department_id" id="department" class="form-control @error('department_id') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        @foreach ($department as $data)
                                          <option value="{{ $data->id }}" {{ old('department_id') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span style="color: red">*</span></label>
                                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        <option value="User" {{ old('role') == "User" ? 'selected' : null }}>User</option>
                                        <option value="Management" {{ old('role') == "Management" ? 'selected' : null }}>Management</option>
                                        <option value="Technician" {{ old('role') == "Technician" ? 'selected' : null }}>Technician</option>
                                        <option value="Admin" {{ old('role') == "Admin" ? 'selected' : null }}>Admin</option>
                                      </select>
                                      @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">Save</button>
                            <a href="{{ route('user.index') }}" class="btn btn-secondary waves-effect">Cancel</a>
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

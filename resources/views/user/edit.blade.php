@extends('layouts.app')

@section('title')
    Edit Data User
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
            
                    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="fullname" class="form-label">Full Name <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="fullname" required="" value="{{ $user->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="username" class="form-label">Username <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" id="username" required="" value="{{ $user->username }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" required="" value="{{ $user->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="office" class="form-label">Office <span style="color: red">*</span></label>
                                    <select name="office_id" id="office" class="form-control @error('office_id') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        @foreach ($office as $data)
                                          <option value="{{ $data->id }}" {{ old('office_id', $user->office_id) == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('office_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department <span style="color: red">*</span></label>
                                    <select name="department_id" id="department" class="form-control @error('department_id') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        @foreach ($department as $data)
                                          <option value="{{ $data->id }}" {{ old('department_id', $user->department_id) == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                            <div class="col-lg-6" id="role-field">
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span style="color: red">*</span></label>
                                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" data-toggle="select2">
                                        <option selected disabled>Please select</option>
                                        <option value="User" {{ old('role', $user->role) == "User" ? 'selected' : null }}>User</option>
                                        <option value="Management" {{ old('role', $user->role) == "Management" ? 'selected' : null }}>Management</option>
                                        <option value="Technician" {{ old('role', $user->role) == "Technician" ? 'selected' : null }}>Technician</option>
                                        <option value="Admin" {{ old('role', $user->role) == "Admin" ? 'selected' : null }}>Admin</option>
                                      </select>
                                      @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if ($user->role == 'Technician')
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label for="handle" class="form-label">Technician Office Handles <span style="color: red">*</span></label>
                                        <select name="handle_id[]" class="form-control select2-multiple @error('handle_id') is-invalid @enderror" data-toggle="select2"
                                        multiple="multiple" data-width="100%" data-placeholder="Please select">
                                            @foreach ($office as $data)
                                            <option {{ $user->officeHandles()->find($data->id) ? 'selected' : '' }} value="{{ $data->id }}" {{ old('handle_id') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('handle_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="isaktif" class="form-label">Status <span style="color: red">*</span></label>
                                        <select name="isaktif" id="isaktif" class="form-select @error('isaktif') is-invalid @enderror">
                                            <option value="1" {{ old('isaktif', $user->isaktif) == 1 ? 'selected' : null }}>Aktif</option>
                                            <option value="0" {{ old('isaktif', $user->isaktif) == 0 ? 'selected' : null }}>Non-Aktif</option>
                                        </select>
                                        @error('isaktif')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                <div class="col-lg-6" id="handle-field" style="display: none">
                                    <div class="mb-3">
                                        <label for="handle" class="form-label">Technician Office Handles <span style="color: red">*</span></label>
                                        <select name="handle_id[]" id="handles" class="form-control select2-multiple @error('handle_id') is-invalid @enderror" data-toggle="select2"
                                        multiple="multiple" data-width="100%" data-placeholder="Please select">
                                            @foreach ($office as $data)
                                            <option {{ $user->officeHandles()->find($data->id) ? 'selected' : '' }} value="{{ $data->id }}" {{ old('handle_id') == $data->id ? 'selected' : null }}>{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('handle_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <label for="isaktif" class="form-label">Status <span style="color: red">*</span></label>
                                        <select name="isaktif" id="isaktif" class="form-select @error('isaktif') is-invalid @enderror">
                                            <option value="1" {{ old('isaktif', $user->isaktif) == 1 ? 'selected' : null }}>Aktif</option>
                                            <option value="0" {{ old('isaktif', $user->isaktif) == 0 ? 'selected' : null }}>Non-Aktif</option>
                                        </select>
                                        @error('isaktif')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif      
                        </div>
            
                        <div class="text-end">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">Update</button>
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
<script type="text/javascript">
    $(document).ready(function () {
        
        //Show and hide field technician office handles
        $('#role-field select[name="role"]').change(function () {
            $('#handle-field').toggle(500);
            $('#handles').val('').change();
        });
    })

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
@extends('layouts.app')

@section('title')
    <p></p>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">                 
                    <form action="{{ route('profile.update', Crypt::encryptString($data->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="mb-3">
                                <h4 style="font-family: Verdana, Geneva, Tahoma, sans-serif">Update Data Diri</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="fullname" class="form-label">Full Name <span style="color: red">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') ?? $data->name }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3" >
                                    <label for="email" class="form-label">Email <span style="color: red">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') ?? $data->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="office" class="form-label">Office <span style="color: red">*</span></label>
                                    <select name="office_id" id="office" class="form-select @error('office_id') is-invalid @enderror">
                                        <option value="">Please select</option>
                                        @foreach ($office as $item)
                                          <option value="{{ $item->id }}" {{ old('office_id', $data->office_id) == $item->id ? 'selected' : null }}>{{ $item->name }}</option>
                                        @endforeach
                                      </select>
                                      @error('office_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                      @enderror
                                </div>
                            </div>
                        </div>
            
                        <div class="text-end">
                            <button class="btn btn-primary waves-effect waves-light" type="submit">{{ $btnSubmit }}</button>
                        </div>
                    </form>

                    <hr>

                    <form action="{{ route('password.update', Crypt::encryptString($data->id)) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div>
                                <h4 style="font-family: Verdana, Geneva, Tahoma, sans-serif">Change Password</h4>
                            </div>
                        </div>
    
                        <div class="alert alert-danger p-2" role="alert">
                           <li>Hanya isi jika ingin mengubah kata sandi</li>
                           <li>Password minimal 8 karakter</li>
                        </div>

                        <div class="col-md-6 border-end-md">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                                <input name="current_password" id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Masukkan kata sandi lama">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> 

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi Baru</label>
                                <input name="password" id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan kata sandi baru">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Ulangi Kata Sandi</label>
                                <input name="password_confirmation" id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                    placeholder="Ulangi kata sandi baru">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">Change Password</button>
                        </div>
    
                    </form>
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
    //End Toastr Notification
</script>
@endpush
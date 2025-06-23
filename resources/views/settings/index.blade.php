@extends('layouts.app', ['title' => 'Pengaturan'])

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pengaturan Toko/Jasa</h6>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="store_name">Nama Toko/Jasa <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('store_name') is-invalid @enderror" 
                               id="store_name" name="store_name" 
                               value="{{ old('store_name', $setting->store_name ?? '') }}" required>
                        @error('store_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="owner_name">Nama Pemilik <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('owner_name') is-invalid @enderror" 
                               id="owner_name" name="owner_name" 
                               value="{{ old('owner_name', $setting->owner_name ?? '') }}" required>
                        @error('owner_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" required>{{ old('address', $setting->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" 
                               value="{{ old('phone', $setting->phone ?? '') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="logo">Logo Toko/Jasa</label>
                        @if(isset($setting) && $setting->logo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $setting->logo) }}" 
                                     alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                <small class="form-text text-muted">Logo saat ini</small>
                            </div>
                        @endif
                        <input type="file" class="form-control-file @error('logo') is-invalid @enderror" 
                               id="logo" name="logo" accept="image/*">
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                        @error('logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
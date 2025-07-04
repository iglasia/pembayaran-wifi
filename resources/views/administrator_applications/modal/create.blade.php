<!-- Modal -->
<div class="modal fade" id="addAdministratorApplicationModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="addAdministratorApplicationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdministratorApplicationModalLabel">Tambah Administrator Aplikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('administrator-aplikasi.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                            id="name" value="{{ old('name') }}" placeholder="Masukkan nama">
                        @error('name')
                            <small class="d-block font-weight-bold invalid-feedback">{{ $errors->first('name') }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position_id">Jabatan</label>
                        <select name="position_id" class="form-control @error('position_id') is-invalid @enderror"
                            id="position_id">
                            <option selected>Pilih..</option>
                            @foreach ($positions as $position)
                                @if ($position->id != 3)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('position_id')
                            <small
                                class="d-block font-weight-bold invalid-feedback">{{ $errors->first('position_id') }}</small>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label for="email">Alamat Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            id="email" value="{{ old('email') }}" placeholder="Masukkan alamat email">
                        @error('email')
                            <small class="d-block font-weight-bold invalid-feedback">{{ $errors->first('email') }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" id="password" placeholder="Masukkan password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="password-button-eye"><i
                                        class="fas fa-eye" id="password-eye-class"></i></button>
                            </div>
                        </div>
                        @error('password')
                            <small
                                class="d-block font-weight-bold invalid-feedback">{{ $errors->first('password') }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Ulangi Password</label>
                        <div class="input-group">
                            <input type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" id="password_confirmation" placeholder="Ulangi password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button"
                                    id="password-confirmation-button-eye"><i class="fas fa-eye"
                                        id="password-confirmation-eye-class"></i></button>
                            </div>
                        </div>
                        @error('password_confirmation')
                            <small
                                class="d-block font-weight-bold invalid-feedback">{{ $errors->first('password_confirmation') }}</small>
                        @enderror
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
            </form>
        </div>
    </div>
</div>

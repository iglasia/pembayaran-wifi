<!-- Modal -->
<div class="modal fade" id="editTransactionModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="editTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTransactionModalLabel">Ubah Tagihan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="client_id">Klien</label>
                                <input type="text" class="form-control" id="client_name" readonly>
                                <input type="hidden" name="client_id" id="client_id">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="internet_package_name">Nama Paket Internet</label>
                                <input type="text" class="form-control" id="internet_package_name" disabled>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="internet_package_price">Harga Paket Internet</label>
                                <input type="text" class="form-control" id="internet_package_price" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-lg-3">
                            <div class="form-group">
                                <label for="day">Hari</label>
                                <select class="form-control" name="day" id="day">
                                    <option>Pilih..</option>
                                    @foreach (range(1, 31) as $day)
                                        <option value="{{ sprintf('%02d', $day) }}"
                                            {{ sprintf('%02d', $day) === date('d') ? 'selected' : '' }}>
                                            {{ sprintf('%02d', $day) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-3">
                            <div class="form-group">
                                <label for="month">Bulan</label>
                                <select class="form-control" name="month" id="month">
                                    <option>Pilih..</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ sprintf('%02d', $month) }}"
                                            {{ sprintf('%02d', $month) === date('m') ? 'selected' : '' }}>
                                            {{ sprintf('%02d', $month) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-3">
                            <div class="form-group">
                                <label for="year">Tahun</label>
                                <input type="number" class="form-control" name="year" id="year"
                                    placeholder="Masukkan tahun">
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="" {{ empty($transaction) ? 'selected' : '' }}>Pilih Status
                                    </option>
                                    <option value="Belum Lunas"
                                        {{ isset($transaction) && $transaction->status == 'Belum Lunas' ? 'selected' : '' }}>
                                        Belum Lunas
                                    </option>
                                    <option value="Lunas"
                                        {{ isset($transaction) && $transaction->status == 'Lunas' ? 'selected' : '' }}>
                                        Lunas
                                    </option>
                                </select>

                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Ubah</button>
            </div>
            </form>
        </div>
    </div>
</div>

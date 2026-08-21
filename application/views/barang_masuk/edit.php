<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Form Edit Barang Masuk
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('barangmasuk') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon">
                                <i class="fa fa-arrow-left"></i>
                            </span>
                            <span class="text">
                                Kembali
                            </span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <?= form_open('barangmasuk/edit/' . $barang_masuk['id_barang_masuk'], [], ['id_barang_masuk' => $barang_masuk['id_barang_masuk'], 'user_id' => $this->session->userdata('login_session')['user']]); ?>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="id_barang_masuk">ID Transaksi</label>
                    <div class="col-md-4">
                        <input value="<?= $barang_masuk['id_barang_masuk']; ?>" type="text" readonly class="form-control">
                        <?= form_error('id_barang_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="tanggal_masuk">Tanggal Masuk</label>
                    <div class="col-md-4">
                        <input value="<?= set_value('tanggal_masuk', $barang_masuk['tanggal_masuk']); ?>" name="tanggal_masuk" id="tanggal_masuk" type="text" class="form-control date" placeholder="Tanggal Masuk...">
                        <?= form_error('tanggal_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="supplier_id">Supplier</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="supplier_id" id="supplier_id" class="select2-supplier form-control">
                                <option value="" disabled>Pilih Supplier</option>
                                <?php foreach ($supplier as $s) : ?>
                                    <option <?= set_select('supplier_id', $s['id_supplier'], $s['id_supplier'] == $barang_masuk['supplier_id']) ?> value="<?= $s['id_supplier'] ?>"><?= $s['nama_supplier'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('supplier/add'); ?>"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>
                        <?= form_error('supplier_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="barang_id">Barang</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <select name="barang_id" id="barang_id" class="select2-barang form-control">
                                <option value="" disabled>Pilih Barang</option>
                                <?php foreach ($barang as $b) : ?>
                                    <option 
                                        <?= set_select('barang_id', $b['id_barang'], $b['id_barang'] == $barang_masuk['barang_id']) ?>
                                        value="<?= $b['id_barang'] ?>"
                                        data-stok="<?= $b['stok'] ?>"
                                        data-satuan-id="<?= $b['satuan_id'] ?>"
                                    >
                                        <?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-primary" href="<?= base_url('barang/add'); ?>">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </div>
                        </div>
                        <?= form_error('barang_id', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="jumlah_masuk">Jumlah Masuk</label>
                    <div class="col-md-5">
                        <div class="input-group">
                            <input value="<?= set_value('jumlah_masuk', $barang_masuk['jumlah_masuk']); ?>" name="jumlah_masuk" id="jumlah_masuk" type="number" class="form-control" placeholder="Jumlah Masuk...">
                            <div class="input-group-append">
                                <span class="input-group-text" id="satuan"><?= $satuan['nama_satuan'] ?? 'Satuan' ?></span>
                            </div>
                        </div>
                        <?= form_error('jumlah_masuk', '<small class="text-danger">', '</small>'); ?>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="keterangan">Keterangan</label>
                    <div class="col-md-5">
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan..."><?= set_value('keterangan', $barang_masuk['keterangan']) ?></textarea>
                    </div>
                </div>
                
                <div class="row form-group">
                    <label class="col-md-4 text-md-right" for="lokasi">Lokasi</label>
                    <div class="col-md-5">
                        <input value="<?= set_value('lokasi', $barang_masuk['lokasi']); ?>" name="lokasi" id="lokasi" type="text" class="form-control" placeholder="Masukkan lokasi...">
                    </div>
                </div>
                
                <div class="row form-group">
                    <div class="col offset-md-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inisialisasi select2
    $('.select2-supplier').select2();
    $('.select2-barang').select2();
    
    // Initialize datepicker
    $('.date').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    
    // Saat barang dipilih, update satuan
    $('#barang_id').change(function() {
        var selectedOption = $(this).find('option:selected');
        var satuanId = selectedOption.data('satuan-id');
        
        // Jika perlu, bisa fetch nama satuan dari AJAX atau langsung tampilkan ID
        // Untuk sederhana, kita tampilkan ID satuan
        $('#satuan').text(satuanId);
    });
});
</script>
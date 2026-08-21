<head>
  <!-- CSS Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  
  <!-- jQuery wajib dimuat dulu -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- JS Select2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
</head>

<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Tambah Material Request</h6>
  </div>
  <div class="card-body">
    <?= $this->session->flashdata('pesan'); ?>
    <?= form_open('material_request/add'); ?>

    <!-- Opsi 1: Pilih Barang dari Daftar -->
    <div class="row form-group">
      <label class="col-md-3 text-md-right" for="id_barang">Pilih Barang dari Daftar</label>
      <div class="col-md-6">
        <select name="id_barang" id="id_barang" class="form-control">
          <option value="" selected>Pilih Barang (Jika ada di daftar)</option>
          <?php foreach ($barang as $b) : ?>
            <option value="<?= $b['id_barang'] ?>" <?= set_select('id_barang', $b['id_barang']); ?>>
              <?= $b['id_barang'] . ' | ' . $b['nama_barang'] ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Pilih barang jika sudah ada di master barang</small>
        <?= form_error('id_barang', '<small class="text-danger">', '</small>'); ?>
      </div>
    </div>

    <!-- Atau -->
    <div class="row form-group">
      <div class="col-md-6 offset-md-3">
        <div class="text-center text-muted my-2">
          <strong>ATAU</strong>
        </div>
      </div>
    </div>

    <!-- Opsi 2: Input Barang Baru Manual -->
    <div class="row form-group">
      <label class="col-md-3 text-md-right" for="barang_minta">Minta Barang Baru</label>
      <div class="col-md-6">
        <input value="<?= set_value('barang_minta'); ?>" name="barang_minta" id="barang_minta" type="text" class="form-control" placeholder="Masukkan nama barang yang diminta">
        <small class="form-text text-muted">Isi nama barang baru jika tidak ada di daftar master barang</small>
        <?= form_error('barang_minta', '<small class="text-danger">', '</small>'); ?>
      </div>
    </div>

    <div class="row form-group">
      <label class="col-md-3 text-md-right" for="id_jenis">Jenis Barang</label>
      <div class="col-md-6">
        <select name="id_jenis" id="id_jenis" class="form-control" required>
          <option value="" selected disabled>Pilih Jenis Barang</option>
          <?php foreach ($jenis as $j) : ?>
            <option value="<?= $j['id_jenis'] ?>" <?= set_select('id_jenis', $j['id_jenis']); ?>>
              <?= $j['nama_jenis'] ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?= form_error('id_jenis', '<small class="text-danger">', '</small>'); ?>
      </div>
    </div>

    <div class="row form-group">
      <label class="col-md-3 text-md-right" for="id_satuan">Satuan Barang</label>
      <div class="col-md-6">
        <select name="id_satuan" id="id_satuan" class="form-control" required>
          <option value="" selected disabled>Pilih Satuan Barang</option>
          <?php foreach ($satuan as $s) : ?>
            <option value="<?= $s['id_satuan'] ?>" <?= set_select('id_satuan', $s['id_satuan']); ?>>
              <?= $s['nama_satuan'] ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?= form_error('id_satuan', '<small class="text-danger">', '</small>'); ?>
      </div>
    </div>

    <div class="row form-group">
      <label class="col-md-3 text-md-right" for="stok">Jumlah Diminta</label>
      <div class="col-md-4">
        <input value="<?= set_value('stok'); ?>" name="stok" id="stok" type="number" class="form-control" placeholder="Jumlah yang diminta" step="0.01" min="0.01" required>
        <?= form_error('stok', '<small class="text-danger">', '</small>'); ?>
      </div>
    </div>

    <div class="row form-group">
      <label class="col-md-3 text-md-right" for="link_rekomendasi">Link Rekomendasi</label>
      <div class="col-md-6">
        <input value="<?= set_value('link_rekomendasi'); ?>" name="link_rekomendasi" id="link_rekomendasi" type="url" class="form-control" placeholder="https://example.com">
        <small class="form-text text-muted">Opsional - link rekomendasi pembelian</small>
        <?= form_error('link_rekomendasi', '<small class="text-danger">', '</small>'); ?>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-6 offset-md-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
        <a href="<?= base_url('material_request') ?>" class="btn btn-light">Kembali</a>
      </div>
    </div>

    <?= form_close(); ?>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#id_barang').select2({
    placeholder: "Pilih Barang (Jika ada di daftar)",
    allowClear: true,
    width: '100%',
    language: {
      noResults: function() {
        return "Data tidak ditemukan";
      }
    }
  });

  $('#id_jenis').select2({
    placeholder: "Pilih Jenis Barang",
    allowClear: true,
    width: '100%',
    language: {
      noResults: function() {
        return "Data tidak ditemukan";
      }
    }
  });

  $('#id_satuan').select2({
    placeholder: "Pilih Satuan Barang",
    allowClear: true,
    width: '100%',
    language: {
      noResults: function() {
        return "Data tidak ditemukan";
      }
    }
  });

  // Auto-clear functionality
  $('#id_barang').on('change', function() {
    if ($(this).val()) {
      $('#barang_minta').val('');
    }
  });

  $('#barang_minta').on('input', function() {
    if ($(this).val().trim() !== '') {
      $('#id_barang').val('').trigger('change');
    }
  });

  // Validasi real-time
  $('form').on('submit', function(e) {
    const idBarang = $('#id_barang').val();
    const barangMinta = $('#barang_minta').val().trim();
    
    if (!idBarang && !barangMinta) {
      alert('Pilih barang dari daftar atau isi nama barang manual');
      e.preventDefault();
      return false;
    }
    
    if (idBarang && barangMinta) {
      alert('Hanya boleh memilih barang dari daftar ATAU mengisi nama barang manual, tidak boleh keduanya');
      e.preventDefault();
      return false;
    }
  });
});
</script>

<style>
.select2-container--default .select2-selection--single {
  height: calc(2.25rem + 2px);
  padding: 0.375rem 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}
.select2-container--default .select2-selection--single:focus {
  border-color: #80bdff;
  outline: 0;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: calc(2.25rem + 2px);
}
</style>
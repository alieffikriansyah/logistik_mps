<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Data Barang
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barang/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Tambah Barang
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card-body border-bottom">
        <form method="get" action="<?= base_url('barang') ?>" class="form-inline">
            <div class="form-group mb-2 mr-2">
                <label for="jenis_filter" class="sr-only">Filter Jenis</label>
                <select name="jenis_filter" id="jenis_filter" class="form-control form-control-sm">
                    <option value="">-- Semua Jenis --</option>
                    <?php foreach ($jenis as $j) : ?>
                        <option value="<?= $j['nama_jenis'] ?>" <?= ($selected_jenis == $j['nama_jenis']) ? 'selected' : '' ?>>
                            <?= $j['nama_jenis'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-sm btn-primary mb-2">
                <i class="fa fa-filter"></i> Filter
            </button>
            <a href="<?= base_url('barang') ?>" class="btn btn-sm btn-secondary mb-2 ml-1">
                <i class="fa fa-refresh"></i> Reset
            </a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Barang</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Jenis Barang</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Lokasi</th>
                    <th>Foto Barang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if ($barang) :
                    foreach ($barang as $b) :
                ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $b['id_barang']; ?></td>
                            <td><?= $b['nama_barang']; ?></td>
                            <td><?= !empty($b['merk']) ? $b['merk'] : '-' ?></td>
                            <td><?= $b['nama_jenis']; ?></td>
                            <td><?= $b['stok']; ?></td>
                            <td><?= $b['nama_satuan']; ?></td>
                            <td><?= !empty($b['lokasi']) ? $b['lokasi'] : '-' ?></td>
                            <td class="text-center">
                                <?php if (!empty($b['foto_barang']) && file_exists(FCPATH . 'assets/uploads/fotobarang/' . $b['foto_barang'])) : ?>
                                    <img src="<?= base_url('assets/uploads/fotobarang/' . $b['foto_barang']); ?>" 
                                        alt="Foto <?= $b['nama_barang']; ?>" 
                                        class="img-thumbnail" 
                                        style="width: 60px; height: 60px; object-fit: cover;"
                                        title="<?= $b['foto_barang']; ?>">
                                <?php else : ?>
                                    <span class="badge badge-secondary">No Image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('barang/edit/') . $b['id_barang'] ?>" class="btn btn-warning btn-circle btn-sm" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a onclick="return confirm('Yakin ingin menghapus barang <?= $b['nama_barang'] ?>?')" 
                                   href="<?= base_url('barang/delete/') . $b['id_barang'] ?>" 
                                   class="btn btn-danger btn-circle btn-sm" 
                                   title="Hapus">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center">
                            Data Kosong
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.img-thumbnail {
    cursor: pointer;
    transition: transform 0.2s;
    border: 1px solid #dee2e6;
}
.img-thumbnail:hover {
    transform: scale(1.1);
}
.badge-secondary {
    background-color: #6c757d;
}
</style>

<script>
// Preview gambar modal
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.img-thumbnail');
    images.forEach(img => {
        img.addEventListener('click', function() {
            const src = this.src;
            const fileName = this.title;
            const modalId = 'imageModal-' + Math.random().toString(36).substr(2, 9);
            
            // Buat modal
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = modalId;
            modal.innerHTML = `
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Foto Barang</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${src}" class="img-fluid" style="max-height: 70vh; max-width: 100%;" alt="Foto Barang">
                            <div class="mt-2">
                                <small class="text-muted">${fileName}</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Initialize modal
            $(modal).modal('show');
            
            // Hapus modal dari DOM saat ditutup
            $(modal).on('hidden.bs.modal', function() {
                $(this).remove();
            });
        });
    });
});

// Filter form
document.getElementById('jenis_filter').addEventListener('change', function() {
    if (this.value) {
        this.form.submit();
    }
});
</script>
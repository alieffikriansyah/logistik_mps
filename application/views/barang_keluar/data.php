<?= $this->session->flashdata('pesan'); ?>
<div class="card shadow-sm border-bottom-primary">
    <div class="card-header bg-white py-3">
        <div class="row">
            <div class="col">
                <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                    Riwayat Data Barang Keluar
                </h4>
            </div>
            <div class="col-auto">
                <a href="<?= base_url('barangkeluar/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                    <span class="icon">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">
                        Input Barang Keluar
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Filter Form -->
        <form method="get" action="<?= base_url('barangKeluar') ?>" class="form-inline mb-3">
            <div class="form-group mr-2">
                <label for="jenis_filter" class="mr-2">Filter Jenis:</label>
                <select name="jenis_filter" id="jenis_filter" class="form-control form-control-sm">
                    <option value="">Semua Jenis</option>
                    <?php foreach ($jenis as $j) : ?>
                        <option value="<?= $j['id_jenis'] ?>" <?= (isset($selected_jenis) && $selected_jenis == $j['id_jenis']) ? 'selected' : '' ?>>
                            <?= $j['nama_jenis'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
            <?php if (isset($selected_jenis) && $selected_jenis) : ?>
                <a href="<?= base_url('barangKeluar') ?>" class="btn btn-secondary btn-sm ml-2">Reset</a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="table table-striped w-100 dt-responsive nowrap" id="dataTable">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>No Transaksi</th>
                        <th>Tanggal Keluar</th>
                        <th>Unit</th>
                        <th>Nama PIC</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th> <!-- TAMBAHKAN KOLOM JENIS -->
                        <th>Jumlah Keluar</th>
                        <th>Keterangan</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if ($barangkeluar) :
                        foreach ($barangkeluar as $bk) :
                            ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $bk['id_barang_keluar']; ?></td>
                                <td><?= date('d-m-Y', strtotime($bk['tanggal_keluar'])); ?></td> <!-- Format tanggal -->
                                <td><span class="badge badge-info"><?= strtoupper($bk['unit']); ?></span></td>
                                <td><?= $bk['nama_pic']; ?></td>
                                <td><?= $bk['nama_barang']; ?></td>
                                <td><?= $bk['nama_jenis']; ?></td> <!-- TAMPILKAN JENIS BARANG -->
                                <td><?= $bk['jumlah_keluar'] . ' ' . $bk['nama_satuan']; ?></td>
                                <td><?= $bk['keterangan']; ?></td>
                                <td><?= $bk['nama']; ?></td>
                                <td>
                                    <!-- Tombol Print -->
                                    <a href="<?= base_url('barangkeluar/print_serah_terima/') . $bk['id_barang_keluar'] ?>" class="btn btn-secondary btn-circle btn-sm" target="_blank">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    <a onclick="return confirm('Yakin ingin hapus?')" href="<?= base_url('barangkeluar/delete/') . $bk['id_barang_keluar'] ?>" class="btn btn-danger btn-circle btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="11" class="text-center"> <!-- Sesuaikan colspan -->
                                Data Kosong
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Filter form auto submit
document.getElementById('jenis_filter').addEventListener('change', function() {
    if (this.value) {
        this.form.submit();
    }
});
</script>
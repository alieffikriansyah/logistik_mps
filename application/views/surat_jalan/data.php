<div class="row justify-content-center">
    <div class="col">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">Data Surat Jalan</h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('SuratJalan/add') ?>" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="icon"><i class="fa fa-plus"></i></span>
                            <span class="text">Tambah Surat Jalan</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Surat Jalan</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Supplier</th>
                                <th>Jumlah</th>
                                <th>Dokumentasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($surat_jalan as $row) : ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><strong>#SJ<?= str_pad($row['id_surat_jalan'], 4, '0', STR_PAD_LEFT) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td>
                                    <?php 
                                    // Barang diambil dari detail_surat_jalan join barang_masuk (butuh join di controller)
                                    // Tampilkan placeholder jika tidak tersedia
                                    echo isset($row['nama_barang']) ? $row['nama_barang'] : '-'; 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    echo isset($row['nama_supplier']) ? $row['nama_supplier'] : '-'; 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    echo isset($row['jumlah_masuk']) ? $row['jumlah_masuk'] . ' unit' : '-'; 
                                    ?>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <i class="fa fa-file"></i> <?= isset($row['total_items']) ? $row['total_items'] : 0 ?> file
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= base_url('SuratJalan/detail/' . $row['id_surat_jalan']) ?>" class="btn btn-sm btn-info">
                                        <i class="fa fa-eye"></i> Detail
                                    </a>
                                    <a href="<?= base_url('SuratJalan/delete/' . $row['id_surat_jalan']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus Surat Jalan?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php if(empty($surat_jalan)): ?>
                            <tr>
                                <td colspan="8" class="text-center">Data tidak tersedia</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

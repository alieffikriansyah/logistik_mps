<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            Detail BAST
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('bast') ?>" class="btn btn-sm btn-secondary btn-icon-split">
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
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">NO BAST</th>
                                <td> <strong>
                                        <?= formatBastId(
                                        $bast['id_bast'], 
                                        $bast['nama_jenis'], 
                                        $bast['unit'], 
                                        $bast['tanggal']) ?>
                                    </strong></td>
                            </tr>
                            <tr>
                                <th>Tanggal BAST</th>
                                <td><?= date('d F Y', strtotime($bast['tanggal'])) ?></td>
                            </tr>
                            <tr>
                                <th>Unit</th>
                                <td>
                                    <?php 
                                    $unit_names = [
                                        'High & Medium Voltage' => 'High & Medium Voltage',
                                        'Power Station 1' => 'Power Station 1', 
                                        'Power Station 2' => 'Power Station 2',
                                        'Power Station 3' => 'Power Station 3',
                                        'Electrical Protecion' => 'EP',
                                        'Electrical Network' => 'Electrical Network',
                                        'North Visual Aid' => 'North Visual Aid',
                                        'South Visual Aid' => 'South Visual Aid',
                                        'Electrical Utility' => 'Electrical Utility',
                                        'UPS & Converter' => 'UPS & Converter',
                                        'Terminal 1' => 'Terminal 1',
                                        'Terminal 2' => 'Terminal 2',
                                        'Terminal 3' => 'Terminal 3',
                                        'Non Terminal Electrical Service' => 'on Terminal Electrical Service'
                                    ];
                                    echo isset($unit_names[$bast['unit']]) ? $unit_names[$bast['unit']] : strtoupper($bast['unit']);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Dibuat Oleh</th>
                                <td><?= $bast['nama_user'] ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php if($bast['status'] == 1): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Dihapus</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Dokumentasi</th>
                                <td>
                                    <?php if (!empty($bast['dokumentasi'])): ?>
                                        <?php
                                        $file_path = FCPATH . 'assets/uploads/bastkeunit/' . $bast['dokumentasi'];
                                        $image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                                        $file_extension = pathinfo($bast['dokumentasi'], PATHINFO_EXTENSION);
                                        ?>
                                        
                                        <?php if (file_exists($file_path) && in_array(strtolower($file_extension), $image_extensions)): ?>
                                            <!-- Jika file adalah gambar, tampilkan thumbnail dengan modal -->
                                            <div class="d-flex align-items-center">
                                                <img src="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" 
                                                    alt="Dokumentasi BAST" 
                                                    class="img-thumbnail mr-2" 
                                                    style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                    data-toggle="modal" data-target="#dokumentasiModal"
                                                    title="Klik untuk melihat lebih besar">
                                                <div>
                                                    <small class="text-muted d-block"><?= $bast['dokumentasi'] ?></small>
                                                    <a href="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                                        <i class="fa fa-external-link-alt"></i> Buka
                                                    </a>
                                                </div>
                                            </div>
                                        <?php elseif (file_exists($file_path)): ?>
                                            <!-- Jika file bukan gambar -->
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 mr-2">
                                                    <i class="fa fa-file fa-2x text-muted"></i>
                                                </div>
                                                <div>
                                                    <small class="text-muted d-block"><?= $bast['dokumentasi'] ?></small>
                                                    <a href="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" 
                                                       target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <!-- Jika file tidak ditemukan -->
                                            <span class="badge badge-warning">
                                                <i class="fa fa-exclamation-triangle"></i> File tidak ditemukan
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">
                                            <i class="fa fa-times"></i> Tidak ada dokumentasi
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h5 class="font-weight-bold mb-3">Daftar Barang</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>No. Transaksi</th>
                                <th>Tanggal Keluar</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Jenis</th>
                                <th>Jumlah Barang Keluar</th>
                                <th>Satuan</th>
                                <th>Unit</th>
                                <th>PIC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($bast_details)): ?>
                                <?php $no = 1; foreach ($bast_details as $detail) : ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $detail['id_barang_keluar'] ?></td>
                                    <td><?= date('d/m/Y', strtotime($detail['tanggal_keluar'])) ?></td>
                                    <td><?= $detail['id_barang'] ?></td>
                                    <td><?= $detail['nama_barang'] ?></td>
                                    <td><?= $detail['nama_jenis'] ?></td>
                                    <td><?= $detail['jumlah_keluar'] ?></td>
                                    <td><?= $detail['nama_satuan'] ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?= isset($unit_names[$detail['unit']]) ? $unit_names[$detail['unit']] : strtoupper($detail['unit']) ?>
                                        </span>
                                    </td>
                                    <td><?= $detail['nama_pic'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada barang</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="row mt-4">
                    <div class="col text-center">
                        <a href="<?= base_url('bast/print_bast/') . $bast['id_bast'] ?>" target="_blank" class="btn btn-primary">
                            <i class="fa fa-print"></i> Print BAST
                        </a>
                        <a href="<?= base_url('bast/delete/') . $bast['id_bast'] ?>" class="btn btn-danger" onclick="return confirm('Hapus BAST?')">
                            <i class="fa fa-trash"></i> Hapus BAST
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar dokumentasi secara besar -->
<?php if (!empty($bast['dokumentasi']) && file_exists($file_path) && in_array(strtolower($file_extension), $image_extensions)): ?>
<div class="modal fade" id="dokumentasiModal" tabindex="-1" role="dialog" aria-labelledby="dokumentasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dokumentasiModalLabel">Dokumentasi BAST</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" 
                     alt="Dokumentasi BAST" 
                     class="img-fluid rounded"
                     style="max-height: 70vh;">
                <p class="mt-3 text-muted">
                    <small><?= $bast['dokumentasi'] ?></small>
                </p>
            </div>
            <div class="modal-footer">
                <a href="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" 
                   target="_blank" class="btn btn-primary">
                    <i class="fa fa-external-link-alt"></i> Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.img-thumbnail {
    transition: transform 0.2s ease-in-out;
}
.img-thumbnail:hover {
    transform: scale(1.05);
}
</style>
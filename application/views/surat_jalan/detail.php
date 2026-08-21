<div class="row justify-content-center">
    <div class="col">
        <div class="card shadow-sm border-bottom-primary">
            <div class="card-header bg-white py-3">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="h5 align-middle m-0 font-weight-bold text-primary">
                            <i class="fas fa-file-alt mr-2"></i>Detail Surat Jalan
                        </h4>
                    </div>
                    <div class="col-auto">
                        <a href="<?= base_url('SuratJalan') ?>" class="btn btn-sm btn-secondary btn-icon-split">
                            <span class="icon"><i class="fa fa-arrow-left"></i></span>
                            <span class="text">Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?= $this->session->flashdata('pesan'); ?>
                
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-lg-6">
                        <!-- Informasi Surat Jalan -->
                        <div class="card mb-4 border-left-primary">
                            <div class="card-header bg-light py-2">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-info-circle mr-1"></i>Informasi Surat Jalan
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered mb-0">
                                    <tr>
                                        <th width="40%" class="bg-light">ID Surat Jalan</th>
                                        <td><strong class="text-primary">#SJ<?= str_pad($surat_jalan['id_surat_jalan'], 4, '0', STR_PAD_LEFT) ?></strong></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Tanggal</th>
                                        <td>
                                            <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                            <?= date('d/m/Y', strtotime($surat_jalan['tanggal'])) ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Keterangan</th>
                                        <td><?= !empty($surat_jalan['keterangan']) ? nl2br(htmlspecialchars($surat_jalan['keterangan'])) : '<span class="text-muted">-</span>' ?></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Status</th>
                                        <td>
                                            <?php if($surat_jalan['status'] == 1): ?>
                                                <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Aktif</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><i class="fas fa-times mr-1"></i>Nonaktif</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Detail Barang Masuk -->
                        <div class="card mb-4 border-left-success">
                            <div class="card-header bg-light py-2">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-boxes mr-1"></i>Detail Barang Masuk
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <?php if (!empty($detail)) : ?>
                                    <div class="table-responsive" style="max-height: 400px;">
                                        <table class="table table-bordered table-hover mb-0">
                                            <thead class="bg-light sticky-top">
                                                <tr>
                                                    <th width="100" class="py-2">ID Barang</th>
                                                    <th class="py-2">Barang</th>
                                                    <th width="100" class="py-2">Jumlah</th>
                                                    <th width="120" class="py-2">Supplier</th>
                                                    <th width="100" class="py-2">Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($detail as $d) : ?>
                                                <tr>
                                                    <td class="font-weight-bold text-primary">#BM<?= $d['id_barang_masuk'] ?></td>
                                                    <td>
                                                        <div class="font-weight-bold"><?= htmlspecialchars($d['nama_barang']) ?></div>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars($d['nama_jenis']) ?> • <?= htmlspecialchars($d['nama_satuan']) ?>
                                                        </small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-info p-2"><?= $d['jumlah_masuk'] ?></span>
                                                    </td>
                                                    <td>
                                                        <small><?= htmlspecialchars($d['nama_supplier']) ?></small>
                                                    </td>
                                                    <td>
                                                        <small><?= date('d/m/Y', strtotime($d['tanggal_masuk'])) ?></small>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada barang masuk terkait</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-lg-6">
                        <!-- Foto Bukti -->
                        <div class="card mb-4 border-left-warning">
                            <div class="card-header bg-light py-2">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-camera mr-1"></i>Foto Bukti
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($surat_jalan['foto_bukti'])) : ?>
                                    <div class="border rounded p-2 mb-3 bg-light">
                                        <img src="<?= base_url('assets/uploads/foto_bukti_surat_jalan/' . $surat_jalan['foto_bukti']) ?>" 
                                             alt="Foto Bukti" 
                                             class="img-fluid rounded shadow-sm" 
                                             style="max-height: 250px; cursor: pointer;" 
                                             onclick="window.open('<?= base_url('assets/uploads/foto_bukti_surat_jalan/' . $surat_jalan['foto_bukti']) ?>', '_blank')">
                                    </div>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="<?= base_url('assets/uploads/foto_bukti_surat_jalan/' . $surat_jalan['foto_bukti']) ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-external-link-alt mr-1"></i>Buka
                                        </a>
                                        <a href="<?= base_url('assets/uploads/foto_bukti_surat_jalan/' . $surat_jalan['foto_bukti']) ?>" 
                                           download 
                                           class="btn btn-sm btn-success">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    </div>
                                <?php else : ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada foto bukti</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Dokumentasi Surat Jalan -->
                        <div class="card mb-4 border-left-info">
                            <div class="card-header bg-light py-2">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-file-archive mr-1"></i>Dokumentasi Surat Jalan
                                    <?php if (!empty($dokumentasi)) : ?>
                                        <span class="badge badge-primary ml-2"><?= count($dokumentasi) ?> file</span>
                                    <?php endif; ?>
                                </h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($dokumentasi)) : ?>
                                    <div class="row">
                                        <?php foreach ($dokumentasi as $doc) : 
                                            $file_ext = pathinfo($doc['nama_file'], PATHINFO_EXTENSION);
                                            $is_image = in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
                                            $is_pdf = strtolower($file_ext) === 'pdf';
                                            $is_doc = in_array(strtolower($file_ext), ['doc', 'docx']);
                                        ?>
                                        <div class="col-sm-6 col-md-4 mb-3">
                                            <div class="card h-100 border shadow-sm">
                                                <div class="card-body text-center p-3">
                                                    <?php if ($is_image) : ?>
                                                        <img src="<?= base_url('assets/uploads/dokumentasi_surat_jalan/' . $doc['nama_file']) ?>" 
                                                             alt="Dokumentasi" 
                                                             class="img-fluid rounded mb-2" 
                                                             style="max-height: 80px; cursor: pointer;"
                                                             onclick="window.open('<?= base_url('assets/uploads/dokumentasi_surat_jalan/' . $doc['nama_file']) ?>', '_blank')">
                                                    <?php elseif ($is_pdf) : ?>
                                                        <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                                    <?php elseif ($is_doc) : ?>
                                                        <i class="fas fa-file-word fa-2x text-primary mb-2"></i>
                                                    <?php else : ?>
                                                        <i class="fas fa-file fa-2x text-secondary mb-2"></i>
                                                    <?php endif; ?>
                                                    
                                                    <div class="file-info">
                                                        <small class="d-block text-truncate font-weight-bold" title="<?= htmlspecialchars($doc['nama_file']) ?>">
                                                            <?= htmlspecialchars($doc['nama_file']) ?>
                                                        </small>
                                                        <small class="text-muted"><?= strtoupper($file_ext) ?></small>
                                                    </div>
                                                    
                                                    <div class="mt-2">
                                                        <a href="<?= base_url('assets/uploads/dokumentasi_surat_jalan/' . $doc['nama_file']) ?>" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-outline-primary btn-block">
                                                            <i class="fas fa-eye mr-1"></i>Lihat
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="text-center py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada dokumentasi</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
}
.card-header {
    border-bottom: 1px solid #e3e6f0;
}
.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}
.file-info {
    min-height: 45px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
</style>
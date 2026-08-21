<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BAST <?= $bast['id_bast'] ?></title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .logo {
            height: 80px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .signature-section {
    margin-top: 40px;
    display: flex;
    justify-content: space-between;
}

.signature-box {
    text-align: center;
    width: 45%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.signature-line {
    margin-top: 70px; /* tambah nilai untuk spasi di atas garis */
    border-top: 1px solid #000;
    width: 75%;
    margin: 0 auto;
}



        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
        .no-border td {
            border: none !important;
            padding: 3px 8px;
        }
        .foto-barang {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .no-image {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border: 1px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #6c757d;
        }
        .text-center {
            text-align: center;
        }
        .dokumentasi-container {
            margin-top: 20px;
            text-align: center;
        }
        .dokumentasi-image {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo -->
        <?php 
        $logo_path = FCPATH . 'assets/uploads/ias.png';
        if (file_exists($logo_path)) : 
        ?>
            <img src="<?= base_url('assets/uploads/ias.png'); ?>" alt="Logo Perusahaan" class="logo">
        <?php endif; ?>
        
        <div class="company-name">PT IAS SUPPORT INDONESIA</div>
        <div class="company-name">Operation & Maintenance Elektrikal</div>
        <div class="company-name">Bandara International Soekarno Hatta (CGK)</div>
        <div>Jl. C3 Jl. Raya Bandara Soekarno Hatta, RT.001/RW.010</div>
        <div>RT.001/RW.010, Pajang, Kec. Benda, Kota Tangerang, Banten 15126</div>
       
        
        <div class="document-title">BERITA ACARA SERAH TERIMA (BAST)</div>
    
    </div>

    <?php if ($bast && !empty($bast_details)): ?>
    <!-- Informasi BAST -->
    <table class="table no-border">
        <tr>
            <td width="20%">Tanggal BAST</td>
            <td width="5%">:</td>
            <td width="75%"><strong>
                <?php
                $hari_indonesia = [
                    'Sunday' => 'Minggu',
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa',
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu'
                ];
                $bulan_indonesia = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember'
                ];
                $tanggal = date('l, d F Y', strtotime($bast['tanggal']));
                $tanggal = str_replace(array_keys($hari_indonesia), array_values($hari_indonesia), $tanggal);
                $tanggal = str_replace(array_keys($bulan_indonesia), array_values($bulan_indonesia), $tanggal);
                echo $tanggal;
                ?>
            </strong></td>
        </tr>
        <tr>
            <td width="20%">No BAST</td>
            <td width="5%">:</td>
            <td width="75%">
                <strong>
                          <?= formatBastId(
                                        $bast['id_bast'], 
                                        $bast['nama_jenis'], 
                                        $bast['unit'], 
                                        $bast['tanggal']) ?>
                </strong>
            </td>
        </tr>
        <tr>
            <td>Pihak Kedua (Yang Menerima)</td>
            <td>:</td>
            <td><strong><?= strtoupper($bast['unit']) ?></strong></td>
        </tr>
        <tr>
            <td>Pihak Pertama (Yang Menyerahkan)</td>
            <td>:</td>
            <td><strong> <?php 
                    if (isset($bast['nama_user']) && !empty($bast['nama_user'])) {
                        echo $bast['nama_user'];
                    } else {
                        echo 'Admin Logistik'; // Fallback jika nama user tidak ada
                    }
                    ?></strong></td>
        </tr>
        <!-- <tr>
            <td>Total Item Barang</td>
            <td>:</td>
            <td><strong><?= count($bast_details) ?> Item</strong></td>
        </tr> -->
    </table>

    <!-- Daftar Barang -->
    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Transaksi</th>
                <th width="15%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="15%">Jenis</th>
                <th width="10%">Jumlah</th>
                <th width="10%">Satuan</th>
                <th width="10%">Foto Barang</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($bast_details as $detail) : 
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $detail['id_barang_keluar'] ?></td>
                <td><?= $detail['id_barang'] ?? '-' ?></td>
                <td><?= $detail['nama_barang'] ?? '-' ?></td>
                <td><?= $detail['nama_jenis'] ?? '-' ?></td>
                <td class="text-center"><?= $detail['jumlah_keluar'] ?></td>
                <td class="text-center"><?= $detail['nama_satuan'] ?? '-' ?></td>
                <td class="text-center">
                    <?php 
                    $foto_path = FCPATH . 'assets/uploads/fotobarang/' . $detail['foto_barang'];
                    if (!empty($detail['foto_barang']) && file_exists($foto_path)) : 
                    ?>
                       <img src="<?= base_url('assets/uploads/fotobarang/' . $detail['foto_barang']); ?>" 
                        alt="Foto <?= $detail['nama_barang']; ?>" 
                        class="foto-barang"
                        title="<?= $detail['foto_barang']; ?>"
                        style="width: 100px; height: auto; border-radius: 8px;">
                    <?php else : ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Keterangan Tambahan -->
    <table class="table no-border">
        <tr>
            <td width="15%">Keterangan</td>
            <td width="5%">:</td>
            <td width="80%">
                Berikut barang - barang diatas telah diterima dengan baik.
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-box">
            <div>PIHAK PERTAMA</div>
            <div>Menyerahkan,</div>
            </br></br></br></br></br>
            <div class="signature-line"></div>
            <div>
                    <?php 
                    if (isset($bast['nama_user']) && !empty($bast['nama_user'])) {
                        echo $bast['nama_user'];
                    } else {
                        echo 'Admin Logistik'; // Fallback jika nama user tidak ada
                    }
                    ?>
            </div>
            <div><strong>Logistik OM Elektrikal</strong></div>
        </div>

        <!-- Kotak Tanda Tangan -->
        <?php if ($show_signature): ?>
        <div class="signature-box">
             <div>&nbsp;</div>
            <div>Menyetujui,</div>
            </br></br></br></br></br>
            <div class="signature-line"></div>
          
            <div><?php echo $coordinator_name; ?></div>
            <div><strong>Senior Koordinator</strong></div>
        </div>
        <?php endif; ?>
        
        <div class="signature-box">
            <div>PIHAK KEDUA</div>
            <div>Menerima,</div>
            </br></br></br></br></br>
            <div class="signature-line"></div>
            <div>(_______________________)</div>
            <div>PIC Unit <?= strtoupper($bast['unit']) ?></div>
            <div><strong>UNIT <?= strtoupper($bast['unit']) ?></strong></div>
        </div>

    </div>

    <!-- Page Break untuk Dokumentasi -->
    <div class="page-break"></div>

    <!-- Dokumentasi BAST -->
    <div class="header">
        <div class="document-title">DOKUMENTASI BERITA ACARA SERAH TERIMA</div>
    </div>

    <div class="dokumentasi-container">
        <?php if (!empty($bast['dokumentasi'])): ?>
            <?php
            $dokumentasi_path = FCPATH . 'assets/uploads/bastkeunit/' . $bast['dokumentasi'];
            $file_extension = pathinfo($bast['dokumentasi'], PATHINFO_EXTENSION);
            $image_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (file_exists($dokumentasi_path) && in_array(strtolower($file_extension), $image_extensions)): 
            ?>
                <img src="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" 
                     alt="Dokumentasi BAST" 
                     class="dokumentasi-image">
                <p><small>File: <?= $bast['dokumentasi'] ?></small></p>
            <?php else: ?>
                <div style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                    <p>Dokumentasi tersedia dalam format file: <?= $bast['dokumentasi'] ?></p>
                    <p>
                        <a href="<?= base_url('assets/uploads/bastkeunit/' . $bast['dokumentasi']); ?>" target="_blank">
                            [Klik untuk melihat dokumentasi]
                        </a>
                    </p>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                <p>Tidak ada dokumentasi untuk BAST ini</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara otomatis pada <?= date('d-m-Y H:i:s') ?>
    </div>

    <?php else: ?>
    <div style="text-align: center; color: red; margin-top: 50px;">
        <h3>DATA BAST TIDAK DITEMUKAN</h3>
    </div>
    <?php endif; ?>

    <script>
        // Auto print ketika halaman loaded
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
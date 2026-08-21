<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title> </title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
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
        }
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 45%;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 100%;
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
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">PT. EXAMPLE COMPANY</div>
        <div>Jl. Contoh Alamat No. 123, Jakarta</div>
        <div>Telp: (021) 123-4567 | Email: info@company.com</div>
        
        <div class="document-title">BERITA ACARA SERAH TERIMA BARANG</div>
    </div>

    <?php if ($transaksi): ?>
    <table class="table no-border">
        <tr>
            <td width="20%">No. Transaksi</td>
            <td width="5%">:</td>
            <td width="75%"><strong><?= $transaksi['id_barang_keluar'] ?></strong></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?= date('d F Y', strtotime($transaksi['tanggal_keluar'])) ?></td>
        </tr>
        <tr>
            <td>Unit</td>
            <td>:</td>
            <td><?= strtoupper($transaksi['unit']) ?></td>
        </tr>
        <tr>
            <td>PIC</td>
            <td>:</td>
            <td><?= $transaksi['nama_pic'] ?></td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode Barang</th>
                <th width="30%">Nama Barang</th>
                <th width="15%">Jenis</th>
                <th width="10%">Jumlah</th>
                <th width="15%">Satuan</th>
                <th width="10%">Foto Barang</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="center">1</td>
                <td><?= $transaksi['id_barang'] ?></td>
                <td><?= $transaksi['nama_barang'] ?></td>
                <td><?= $transaksi['nama_jenis'] ?></td>
                <td align="center"><?= $transaksi['jumlah_keluar'] ?></td>
                <td align="center"><?= $transaksi['nama_satuan'] ?></td>
                <td align="center">
                    <?php 
                    $foto_path = FCPATH . 'assets/uploads/fotobarang/' . $transaksi['foto_barang'];
                    if (!empty($transaksi['foto_barang']) && file_exists($foto_path)) : 
                    ?>
                        <img src="<?= base_url('assets/uploads/fotobarang/' . $transaksi['foto_barang']); ?>" 
                             alt="Foto <?= $transaksi['nama_barang']; ?>" 
                             class="foto-barang">
                    <?php else : ?>
                        <div class="no-image">No Image</div>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div>Yang Menyerahkan,</div>
            <div class="signature-line"></div>
            <div>(_______________________)</div>
            <div>Gudang/Admin</div>
        </div>
        
        <div class="signature-box">
            <div>Yang Menerima,</div>
            <div class="signature-line"></div>
            <div>(_______________________)</div>
            <div>PIC Unit <?= strtoupper($transaksi['unit']) ?></div>
        </div>
    </div>

    <div class="footer">
        Dokumen ini dicetak secara otomatis pada <?= date('d-m-Y H:i:s') ?>
    </div>

    <?php else: ?>
    <div style="text-align: center; color: red; margin-top: 50px;">
        <h3>DATA TRANSAKSI TIDAK DITEMUKAN</h3>
    </div>
    <?php endif; ?>
</body>
</html>
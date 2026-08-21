<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Data Barang</x:Name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #333333;
        }
        .page-title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            color: #1E293B;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #2B3E50;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 9pt;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #1E293B;
            height: 24pt;
            padding: 4px 6px;
        }
        .data-table td {
            border: 1px solid #CBD5E1;
            padding: 4px 6px;
            vertical-align: middle;
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .row-even {
            background-color: #FFFFFF;
        }
        .row-odd {
            background-color: #F8FAFC;
        }
        .str-format {
            mso-number-format: "\@";
        }
        .num-format {
            mso-number-format: "#,##0";
        }
        .img-cell {
            text-align: center;
            vertical-align: middle;
        }
        .img-thumb {
            width: 30px;
            height: 30px;
            object-fit: cover;
        }
        .no-img {
            color: #999999;
            font-style: italic;
            font-size: 8pt;
        }
    </style>
</head>
<body>

    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td colspan="9" class="page-title">Barang | LOGISTIK MPS</td>
        </tr>
    </table>

    <table class="data-table">
        <col width="35">   <!-- No. -->
        <col width="90">   <!-- ID Barang -->
        <col width="180">  <!-- Nama Barang -->
        <col width="120">  <!-- Merk -->
        <col width="120">  <!-- Jenis Barang -->
        <col width="60">   <!-- Stok -->
        <col width="60">   <!-- Satuan -->
        <col width="120">  <!-- Lokasi -->
        <col width="90">   <!-- Foto Barang -->
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
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (!empty($barang)) :
                foreach ($barang as $b) :
                    $row_class = ($no % 2 == 0) ? 'row-odd' : 'row-even';
                    $foto_path = FCPATH . 'assets/uploads/fotobarang/' . $b['foto_barang'];
                    $has_foto = !empty($b['foto_barang']) && file_exists($foto_path);
                    $foto_url = base_url('assets/uploads/fotobarang/' . $b['foto_barang']);
            ?>
                    <tr class="<?= $row_class; ?>" style="height: 30pt;">
                        <td class="text-center"><?= $no++; ?></td>
                        <td class="text-center str-format"><?= $b['id_barang']; ?></td>
                        <td class="text-left"><?= $b['nama_barang']; ?></td>
                        <td class="text-left"><?= !empty($b['merk']) ? $b['merk'] : '-'; ?></td>
                        <td class="text-left"><?= $b['nama_jenis']; ?></td>
                        <td class="text-center num-format"><?= $b['stok']; ?></td>
                        <td class="text-center"><?= $b['nama_satuan']; ?></td>
                        <td class="text-left"><?= !empty($b['lokasi']) ? $b['lokasi'] : '-'; ?></td>
                        <td class="img-cell">
                            <?php if ($has_foto) : ?>
                                <img src="<?= $foto_url; ?>" width="30" height="30" class="img-thumb" alt="Foto">
                            <?php else : ?>
                                <span class="no-img">No Image</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="text-center" style="height: 30pt; color: #999999;">Data tidak ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>

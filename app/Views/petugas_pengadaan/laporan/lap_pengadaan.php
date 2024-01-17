<!-- app/Views/admin/hasil_pdf.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengadaan Barang</title>
    <style>
        body {
            text-align: center;
        }

        header {
            margin-bottom: 20px;
            position: relative;
            height: 80px;
            /* Sesuaikan tinggi jika diperlukan */
        }

        header img {
            max-width: 100px;
            max-height: 80px;
            /* Sesuaikan tinggi jika diperlukan */
            position: absolute;
            top: 0;
            left: 0;
        }

        header div {
            text-align: center;
            margin: 0 auto;
            max-width: 600px;
            /* Sesuaikan lebar maksimum jika diperlukan */
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        footer {
            text-align: right;
            margin-top: 50px;
        }

        footer p {
            text-align: right;
            margin-bottom: 10px;
        }

        footer div {
            text-align: right;
            border-top: 1px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Update the header styles -->
    <header style="text-align: center;">
        <img src="img/logo.png" width="10%" height="10%" alt="Logo BPS Kota A">
        <div>
            <h2 style="margin-bottom: 5px;">BPS Kota Pekalongan</h2>
            <p style="margin: 0;">Laporan Pengadaan Barang</p>
            <p style="text-align: center; margin-bottom: 20px;">
                Periode:
                <?= strftime('%d-%m-%Y', strtotime($tanggalMulai)); ?>
                Sampai Dengan
                <?= strftime('%d-%m-%Y', strtotime($tanggalAkhir)); ?>
            </p>
        </div>
    </header>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Spesifikasi</th>
                <th>Jumlah</th>
                <th>Tahun Periode</th>
                <th>Alasan Pengadaan</th>
                <th>Tanggal Pengajuan</th>
                <th>Tanggal Proses</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Status Akhir</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($pengadaan as $num => $row): ?>
            <tr>
                <td><?=$num + 1;?></td>
                <td><?=$row['nama_barang'];?>
                </td>
                <td><?=$row['spesifikasi'];?>
                </td>
                <td><?=$row['jumlah'];?></td>
                <td><?=$row['tahun_periode'];?>
                </td>
                <td><?=$row['alasan_pengadaan'];?>
                </td>
                <td><?=$row['tgl_pengajuan'];?>
                </td>
                <td><?=$row['tgl_proses'];?></td>
                <td><?=$row['tgl_selesai'];?>
                </td>
                <td><?=$row['status'];?></td>
                <td><?=$row['status_akhir'];?>
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>
    </table>

    <footer>
        <div>
            <p>Dibuat oleh:</p>
            <br>
            Avia Dwi Susanti
        </div>
    </footer>
</body>

</html>
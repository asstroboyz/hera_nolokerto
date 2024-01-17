<!-- app/Views/admin/hasil_pdf.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Cetak Data PDF</title>
    <!-- Tambahkan stylesheet atau styling sesuai kebutuhan -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Data Pengadaan Barang</h2>

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User ID</th>
                                    <th>Balasan ID</th>
                                    <th>Nama Pengaju</th>
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

                                <?php foreach ($pengadaan as $row): ?>
                                    <tr>
                                        <td><?=$row['id'];?></td>
                                        <td><?=$row['id_user'];?></td>
                                        <td><?=$row['id_balasan_pengadaan'];?></td>
                                        <td><?=$row['nama_pengaju'];?></td>
                                        <td><?=$row['nama_barang'];?></td>
                                        <td><?=$row['spesifikasi'];?></td>
                                        <td><?=$row['jumlah'];?></td>
                                        <td><?=$row['tahun_periode'];?></td>
                                        <td><?=$row['alasan_pengadaan'];?></td>
                                        <td><?=$row['tgl_pengajuan'];?></td>
                                        <td><?=$row['tgl_proses'];?></td>
                                        <td><?=$row['tgl_selesai'];?></td>
                                        <td><?=$row['status'];?></td>
                                        <td><?=$row['status_akhir'];?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
</body>
</html>

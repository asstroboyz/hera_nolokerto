<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Cetak Data Barang Masuk PDF</title>
    <!-- Tambahkan stylesheet atau styling sesuai kebutuhan -->
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
            width: 100%;
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

    <header style="text-align: left;"> <!-- Mengubah posisi teks pada header ke kiri -->
        <img src="img/logo.png" width="10%" height="10%" alt="Logo BPS Kota A">
        <div>
            <h2 style="margin-bottom: 5px;">BPS Kota Pekalongan</h2>
            <p style="margin: 0;">Laporan Barang Masuk</p>
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
                <th>Kode Barang</th>
                <th>Nama Barang</th>

                <th>Tanggal Barang Masuk</th>



                <th>Jumlah Penambahan</th>

            </tr>
        </thead>

        <tbody>

            <?php foreach ($atk as $num => $row): ?>
            <tr>
                <td><?=$num + 1;?></td>
                <td><?=$row['kode_barang'];?>
                </td>
                <td><?=$row['nama_barang'];?>
                </td>

                <td><?= date("d-m-Y", strtotime($row['tanggal_barang_masuk'])); ?>
                </td>



                <td><?=$row['jumlah_perubahan'];?>
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
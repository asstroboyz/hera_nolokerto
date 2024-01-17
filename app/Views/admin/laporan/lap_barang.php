<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Pengadaan Barang</title>
    <style>
        header {
            margin-bottom: 20px;
            position: relative;
        }

        header img {
            max-width: 100px;
            max-height: 100px;
            position: absolute;
            top: 0;
            left: 0;
        }

        header div {
            text-align: center;
            margin-left: 120px;
            /* Adjust left margin to prevent overlap with the logo */
        }

        table {
            width: 100%;
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

    <header>
        <img src="img/logo.png" width="10%" height="10%" alt="Logo BPS Kota A">
        <div>
            <h2>BPS Kota Pekalongan</h2>
            <p>Laporan Daftar Barang ATK</p>
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
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Satuan Barang</th>
                <th>Jenis Barang</th>
                <th>Stok</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($barang as $row): ?>
            <tr>
                <td><?=$row['kode_barang'];?>
                </td>
                <td><?=$row['nama_barang'];?>
                </td>
                <td><?=$row['satuan_barang'];?>
                </td>
                <td><?=$row['jenis_barang'];?>
                </td>
                <td><?=$row['stok'];?></td>
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
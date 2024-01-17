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
            <p>Laporan Daftar Inventaris Barang</p>
        </div>
    </header>
    <div>
        <p> NAMA UAKPB : BADAN PUSAT STATISTIK KOTA PEKALONGAN</p>
        <p> KODE UAKPB : 054010300018928000KD </p>
    </div>
    <table>
        <thead>
            <tr style="text-align: center;">
                <th style="text-align: center;">No</th>
                <th style="text-align: center;">Kode Barang</th>
                <th style="text-align: center;">Nama Barang</th>
                <th style="text-align: center;">Merk</th>
                <th style="text-align: center;">Tanggal Perolehan</th>
                <th style="text-align: center;">Keterangan</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($inventaris as $num => $row): ?>
            <tr>
                <td><?=$num + 1;?></td>
                <td><?=$row['kode_barang'];?>
                </td>


                <td style="text-align: center">
                    <?=$row['nama_barang'];?>
                </td>
                <td style="text-align: center" width="20%">
                    <?=$row['merk'];?>
                </td>
                <td style="text-align: center">
                    <?=$row['tgl_perolehan'];?>
                </td>
                <td style="text-align: center">
                    Inventaris
                </td>
            </tr>
            <?php endforeach; ?>

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
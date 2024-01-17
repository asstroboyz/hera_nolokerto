<!-- app/Views/admin/laporan/lap_inventaris.php -->

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

        @media print {
            /* Aturan CSS untuk mode cetak */
            img.content-img {
                max-width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>

    <h2>Data Inventaris</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>

                <th>File</th>
                <th>File</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Deleted At</th>
            </tr>
        </thead>

        <tbody>

               <?php foreach ($inventaris as $row): ?>
                <tr>
                    <td><?=$row['id'];?></td>
                    <td><?=base_url() . $row['file'];?></td>
                    <td><img src="<?="../" . $row['file'];?>" alt="Gambar"></td>

                    <td><?=$row['created_at'];?></td>
                    <td><?=$row['updated_at'];?></td>
                    <td><?=$row['deleted_at'];?></td>
                </tr>
            <?php endforeach;?>

        </tbody>
    </table>
</body>
</html>

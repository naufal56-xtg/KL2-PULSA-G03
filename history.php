<?php
require_once('./connection.php');


$id_user = $_GET['id'];

function getData()
{
    global $user, $connect, $id_user;

    $query = mysqli_query($connect, "SELECT * FROM users WHERE id_user='$id_user'");
    $user = mysqli_fetch_array($query);
}


getData();

$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

$data = mysqli_query($connect, "SELECT * FROM histories");
$jumlah_data = mysqli_num_rows($data);
$total_halaman = ceil($jumlah_data / $batas);

$dataRiwayat = mysqli_query($connect, "SELECT * FROM histories  WHERE user_id='$id_user' ORDER BY tanggal DESC LIMIT $halaman_awal, $batas");
// $jumlah_riwayat = mysqli_num_rows($dataRiwayat);
$index = $halaman_awal + 1;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Pulsa | Riwayat Transaksi</title>
    <!-- //TODO File CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- //TODO Bootstrap 5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <!-- //TODO Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <!-- //TODO FontAwesome -->
    <!-- FontAwesome 6.2.0 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src='http://code.jquery.com/jquery-1.9.1.min.js'></script>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css' rel='stylesheet' />
    <script src='https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js'></script>

</head>

<body>


    <header>
        <nav class="navbar">
            <i class="fas fa-wallet fa-2xl "></i>
            <h1 class="txt-rp">Rp. <?= number_format($user['saldo']); ?></h1>

            <h1 class="txt-user"><?= $user['name']; ?></h1>
            <a href="" class="icon-user">
                <i class="fa-regular fa-circle-user fa-2xl"></i>
            </a>
        </nav>
    </header>

    <aside>
        <div class="sidebar">
            <h1 class="txt-adm">ADMIN</h1>
            <ul>
                <li>
                    <a href="index.php?id=<?= $user['id_user']; ?>" class="btn-topup">Top Up</a>
                </li>
                <li>
                    <a href="pulsa.php?id=<?= $user['id_user']; ?>" class="btn-send">Kirim Pulsa</a>
                </li>
                <li>
                    <a href="history.php?id=<?= $user['id_user']; ?>&halaman=1" class="btn-history act">Riwayat</a>
                </li>

            </ul>
        </div>
    </aside>

    <content>
        <div class="container d-flex justify-content-center">
            <div class="card col-10 card-riwayat">
                <div class="card-body">
                    <h2 class="card-title">Riwayat Transaksi</h2>

                    <table class="table table-bordered form-saldo">
                        <thead class="thead">
                            <tr class="text-center">
                                <th>No</th>
                                <th>No Kartu</th>
                                <th>Provider</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php while ($riwayat = mysqli_fetch_assoc($dataRiwayat)) { ?>
                                <?php
                                $date = $riwayat['tanggal'];
                                $newDate = date('d-m-Y', strtotime($date));
                                $hour = date('H:i', strtotime($date));

                                ?>
                                <tr class="text-center">
                                    <td><?= $index++; ?></td>
                                    <td><?= $riwayat['no_kartu']; ?></td>
                                    <td><?= strtoupper($riwayat['prov']); ?></td>
                                    <td>Rp. <?= number_format($riwayat['nominal']); ?></td>
                                    <td><?= $newDate; ?></td>
                                    <td><?= $hour; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <nav class="d-flex justify-content-center">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" <?php if ($halaman > 1) {
                                                            echo "href='?id=$id_user&halaman=$previous'";
                                                        } ?> aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <?php for ($page = 1; $page <= $total_halaman; $page++) { ?>
                                <li class="page-item">
                                    <a class="page-link <?= ($_GET['halaman'] == $page) ? 'active' : ''; ?>" href="?id=<?= $id_user; ?>&halaman=<?= $page; ?>">
                                        <?= $page; ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li class="page-item">
                                <a class="page-link" <?php if ($halaman < $total_halaman) {
                                                            echo "href='?id=$id_user&halaman=$next'";
                                                        } ?> aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>

        </div>


    </content>




    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
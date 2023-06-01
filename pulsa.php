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



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Pulsa | Beli Pulsa</title>
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
                    <a href="pulsa.php?id=<?= $user['id_user']; ?>" class="btn-send act">Kirim Pulsa</a>
                </li>
                <li>
                    <a href="history.php?id=<?= $user['id_user']; ?>&halaman=1" class="btn-history">Riwayat</a>
                </li>

            </ul>
        </div>
    </aside>

    <content>
        <div class="container d-flex justify-content-center">
            <div class="card col-6">
                <div class="card-body">
                    <h2 class="card-title">Pulsa</h2>
                    <form method="POST">
                        <div class="mb-3 form-saldo">
                            <input type="number" class="form-control" name="no_kartu" placeholder="Masukan Nomor HP">
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" name="nominal" placeholder="Masukan Nominal Pembelian">
                        </div>
                        <div class="mb-3">
                            <label for="tsel">Telkomsel</label>
                            <input type="radio" class="tsel" id="tsel" value="tsel" name="prov">
                            <label for="tri">3 (Tri)</label>
                            <input type="radio" class="tri" id="tri" value="tri" name="prov">
                            <label for="xl">XL</label>
                            <input type="radio" class="xl" id="xl" value="xl" name="prov">
                            <label for="im3">IM3</label>
                            <input type="radio" class="im3" id="im3" value="im3" name="prov">
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit" onclick="notif()" class="btn btn-primary btn-kirim">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </content>



    <?php

    if (isset($_POST['submit'])) {
        $no_kartu = $_POST['no_kartu'];
        $prov = $_POST['prov'];
        $nominal = $_POST['nominal'];
        $saldo = $user['saldo'];

        if ($no_kartu == 0 || $no_kartu == null) {
            echo "<script>
            toastr.warning('', 'No Kartu Tidak Boleh (Kosong)', {
                timeOut: 3000
            });
            </script>";
        } else if ($nominal == 0 || $nominal == null) {
            echo "<script>
            toastr.warning('', 'Nominal Pembelian Tidak Boleh (Kosong)', {
                timeOut: 3000
            });
            </script>";
        } else if ($prov == null) {
            echo "<script>
            toastr.warning('', 'Silahkan Pilih Provider Terlebih Dahulu', {
                timeOut: 3000
            });
            </script>";
        } else if ($saldo < $nominal) {
            echo "<script>
                toastr.warning('Silahkan Top Up Terlebih Dahulu !', 'Saldo Tidak Mencukupi', {
                    timeOut: 3000
                });
                </script>";
        } else {
            $insert = mysqli_query($connect, "INSERT INTO histories (no_kartu, prov, nominal, user_id) VALUES ('$no_kartu', '$prov', '$nominal', '$id_user')");

            if ($insert) {
                $user_saldo = $saldo - $nominal;
                $update = mysqli_query($connect, "UPDATE users SET saldo='$user_saldo' WHERE id_user='$id_user' ");

                getData();
                echo "<script>
                        toastr.success('Terima Kasih', 'Selamat Pembelian Pulsa Berhasil', {
                            timeOut: 3000
                        });    
                    </script>";
            } else {
                $error =  mysqli_error($connect);
                echo "<script>
                        toastr.error('Error', '$error', {
                            timeOut: 5000
                        });    
                    </script>";
            }
        }
    }


    ?>




    <script src="assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
<?php
require_once('./connection.php');

$id_user = $_GET['id'];

if (!empty($id_user)) {
    function getData()
    {
        global $user, $connect, $id_user;

        $query = mysqli_query($connect, "SELECT * FROM users WHERE id_user='$id_user'");
        $user = mysqli_fetch_array($query);
    }
} else {
    function getData()
    {
        global $user, $connect;

        $query = mysqli_query($connect, "SELECT * FROM users WHERE id_user='1'");
        $user = mysqli_fetch_array($query);
    }
}



getData();




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Pulsa | Top Up</title>
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
                    <a href="index.php?id=<?= $user['id_user']; ?>" class="btn-topup act">Top Up</a>
                </li>
                <li>
                    <a href="pulsa.php?id=<?= $user['id_user']; ?>" class="btn-send">Kirim Pulsa</a>
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
                    <h2 class="card-title">Top Up</h2>
                    <form method="POST">
                        <div class="mb-3 form-saldo">
                            <input type="number" class="form-control" name="saldo">
                        </div>
                        <div class="d-grid">
                            <button type="submit" name="submit" onclick="notif()" class="btn btn-primary btn-kirim">Top Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </content>



    <?php

    if (isset($_POST['submit'])) {
        $saldo = $_POST['saldo'];


        if ($saldo == 0 || $saldo == null) {
            echo "<script>
                toastr.warning('', 'Top Up Saldo Tidak Boleh (Kosong)', {
                    timeOut: 3000
                });
                </script>";
        } else if ($saldo <= 5000) {
            echo "<script>
                toastr.warning('', 'Minimal Top Up Saldo Rp. 5.000', {
                    timeOut: 3000
                });
                </script>";
        } else {
            $user_id = $user['id_user'];
            $user_saldo = $user['saldo'];
            if ($user_saldo != 0) {
                $user_saldo += $saldo;
                $update = mysqli_query($connect, "UPDATE users SET saldo='$user_saldo' WHERE id_user='$user_id' ");
            } else {
                // echo $user_saldo;
                $update = mysqli_query($connect, "UPDATE users SET saldo='$saldo' WHERE id_user='$user_id' ");
            }

            if ($update) {
                getData();
                echo "<script>
                        toastr.success('Terima Kasih', 'Selamat Saldo Anda Berhasil Di Top Up', {
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
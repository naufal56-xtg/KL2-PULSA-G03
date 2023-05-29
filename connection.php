<?php

$host = 'localhost';
$username = 'root';
$password = '021admin56';
$db = 'pulsa_kl2_db';


$connect = mysqli_connect($host, $username, $password, $db);

// if (!$connect) {
//     global $connect;
//     echo 'Error : ' . mysqli_error($connect);
// } else {
//     echo 'Success';
// }

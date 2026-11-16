<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bantul";

$conn = mysqli_connect($host, $user, $pass, $db);

if (isset($_POST['nama'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kalurahan = mysqli_real_escape_string($conn, $_POST['kalurahan']);
    $lat = mysqli_real_escape_string($conn, $_POST['latitude']);
    $lng = mysqli_real_escape_string($conn, $_POST['longitude']);

    $query = "INSERT INTO industri (nama, kalurahan, latitude, longitude) VALUES ('$nama', '$kalurahan', '$lat', '$lng')";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>
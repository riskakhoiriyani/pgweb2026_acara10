<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bantul";

$conn = mysqli_connect($host, $user, $pass, $db);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM industri WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal menghapus data.";
    }
}
?>
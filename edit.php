<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bantul";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM industri WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kalurahan = mysqli_real_escape_string($conn, $_POST['kalurahan']);
    $lat = mysqli_real_escape_string($conn, $_POST['latitude']);
    $lng = mysqli_real_escape_string($conn, $_POST['longitude']);

    $query = "UPDATE industri SET nama='$nama', kalurahan='$kalurahan', latitude='$lat', longitude='$lng' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit Data Industri</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 30px; background: #fff0f5; }
        .container { width: 400px; background: #fff; padding: 20px; border: 1px solid #ffb6c1; border-radius: 5px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #db7093; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; font-size: 12px; border: 1px solid #ffb6c1; border-radius: 3px; }
        .btn { background-color: #ff69b4; color: white; border: none; padding: 8px 15px; cursor: pointer; font-size: 12px; border-radius: 3px; }
        .btn:hover { background-color: #ff1493; }
    </style>
</head>
<body>
    <div class="container">
        <h3>Edit Data Industri</h3>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
            <div class="form-group">
                <label>Nama Objek / Industri:</label>
                <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label>Kalurahan:</label>
                <input type="text" name="kalurahan" value="<?php echo htmlspecialchars($data['kalurahan']); ?>">
            </div>
            <div class="form-group">
                <label>Latitude:</label>
                <input type="text" name="latitude" value="<?php echo htmlspecialchars($data['latitude']); ?>" required>
            </div>
            <div class="form-group">
                <label>Longitude:</label>
                <input type="text" name="longitude" value="<?php echo htmlspecialchars($data['longitude']); ?>" required>
            </div>
            <button type="submit" name="update" class="btn">Update Data</button>
            <a href="index.php" style="font-size: 12px; margin-left: 10px; color: #666; text-decoration: none;">Kembali</a>
        </form>
    </div>
</body>
</html>
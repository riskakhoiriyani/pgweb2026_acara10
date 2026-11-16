<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bantul";

$conn = mysqli_connect($host, $user, $pass, $db);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM industri WHERE id = $id");
    $data = mysqli_fetch_assoc($result);
}
diklik
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
        body { font-family: Arial, sans-serif; padding: 30px; background: #f9f9f9; }
        .form-container { width: 400px; background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; margin: auto; }
        .form-group { margin-bottom: 12px; }
        label { display: block; font-weight: bold; margin-bottom: 4px; font-size: 13px; }
        input[type="text"] { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background-color: #2c3e50; color: white; border: none; padding: 8px 15px; cursor: pointer; border-radius: 3px; }
        button:hover { background-color: #34495e; }
        .back-btn { background-color: #95a5a6; margin-left: 10px; text-decoration: none; color: white; padding: 8px 15px; display: inline-block; border-radius: 3px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="form-container">
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
                <input type="text" name="latitude" value="<?php echo $data['latitude']; ?>" required>
            </div>
            <div class="form-group">
                <label>Longitude:</label>
                <input type="text" name="longitude" value="<?php echo $data['longitude']; ?>" required>
            </div>
            <button type="submit" name="update">Simpan Perubahan</button>
            <a href="index.php" class="back-btn">Batal</a>
        </form>
    </div>
</body>
</html>
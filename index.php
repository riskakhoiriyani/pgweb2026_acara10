<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "bantul";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Geoportal Bantul</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css">
    <style>
        body { margin: 0; padding: 0; display: flex; height: 100vh; font-family: Arial, sans-serif; overflow: hidden; }
        #map { flex: 1; height: 100%; transition: all 0.3s ease; }
        
        #sidebar { 
            width: 450px; 
            height: 100%; 
            overflow-y: auto; 
            padding: 20px; 
            box-sizing: border-box; 
            background: #fff0f5; 
            border-left: 2px solid #ffb6c1; 
            position: relative;
            transition: transform 0.3s ease;
        }
        #sidebar.hidden {
            transform: translateX(100%);
        }
        
        #toggle-btn {
            position: absolute;
            left: -40px;
            top: 20px;
            background: #ff69b4;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 4px 0 0 4px;
            z-index: 1000;
        }
        #toggle-btn:hover {
            background: #ff1493;
        }

        h2, h3 { margin-top: 0; color: #db7093; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; margin-bottom: 20px; }
        th, td { border: 1px solid #ffb6c1; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #ff69b4; color: white; }
        tr:nth-child(even) { background-color: #fff0f5; }
        .btn { background-color: #ff69b4; color: white; border: none; padding: 5px 10px; cursor: pointer; font-size: 11px; text-decoration: none; display: inline-block; border-radius: 3px; }
        .btn:hover { background-color: #ff1493; }
        .btn-danger { background-color: #ffb6c1; color: #8b0000; }
        .btn-danger:hover { background-color: #ff69b4; color: white; }
        .btn-warning { background-color: #ffc0cb; color: #4a0000; }
        .btn-warning:hover { background-color: #ffb6c1; }
        .form-group { margin-bottom: 10px; }
        label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 3px; color: #db7093; }
        input[type="text"] { width: 100%; padding: 6px; box-sizing: border-box; font-size: 12px; border: 1px solid #ffb6c1; border-radius: 3px; }
    </style>
</head>
<body>

    <div id="map"></div>

    <div id="sidebar">
        <button id="toggle-btn" onclick="toggleSidebar()">☰</button>
        
        <h2>Sebaran Industri Manufaktur di Kabupaten Bantul</h2>

        <div style="background: #ffffff; padding: 15px; border: 1px solid #ffb6c1; border-radius: 5px; margin-bottom: 20px;">
            <h3>Input Data</h3>
            <form method="POST" action="insert.php">
                <div class="form-group">
                    <label>Nama Objek / Industri:</label>
                    <input type="text" name="nama" required>
                </div>
                <div class="form-group">
                    <label>Kalurahan:</label>
                    <input type="text" name="kalurahan">
                </div>
                <div class="form-group">
                    <label>Latitude:</label>
                    <input type="text" name="latitude" required placeholder="Contoh: -7.875383">
                </div>
                <div class="form-group">
                    <label>Longitude:</label>
                    <input type="text" name="longitude" required placeholder="Contoh: 110.426212">
                </div>
                <button type="submit" class="btn">Simpan Data</button>
            </form>
        </div>

        <h3>Data Industri</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Kalurahan</th>
                    <th>Lat / Long</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM industri";
                $result = mysqli_query($conn, $query);
                
                $no = 1;
                $markersData = [];

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id = $row['id'];
                        $lat = $row['latitude'];
                        $lng = $row['longitude'];
                        $nama = $row['nama'];
                        $kalurahan = isset($row['kalurahan']) ? $row['kalurahan'] : '-';

                        echo "<tr>";
                        echo "<td>" . $no++ . "</td>";
                        echo "<td>" . htmlspecialchars($nama) . "</td>";
                        echo "<td>" . htmlspecialchars($kalurahan) . "</td>";
                        echo "<td>" . $lat . "<br>" . $lng . "</td>";
                        echo "<td>
                                <a href='edit.php?id=$id' class='btn btn-warning'>Edit</a> 
                                <a href='delete.php?id=$id' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";

                        $markersData[] = [
                            'lat' => $lat,
                            'lng' => $lng,
                            'nama' => $nama
                        ];
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Belum ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([-7.875383, 110.426212], 12);

        var basemap1 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        });
        var basemap2 = L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: 'Map data &copy; Google'
        });
        basemap1.addTo(map);

        var markerLayer = L.layerGroup().addTo(map);
        var dataIndustri = <?php echo json_encode($markersData); ?>;

        dataIndustri.forEach(function(item) {
            var marker = L.marker([item.lat, item.lng]).bindPopup(item.nama);
            marker.addTo(markerLayer);
        });

        L.control.layers({ "Esri Imagery": basemap1, "Google Street": basemap2 }, { "Data Industri": markerLayer }, { collapsed: false }).addTo(map);
        L.control.scale({ maxWidth: 150, position: 'bottomright' }).addTo(map);

        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
            
            setTimeout(function() {
                map.invalidateSize();
            }, 300);
        }
    </script>
</body>
</html>
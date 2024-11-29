<?php
include 'koneksi.php';

$sql = "SELECT * FROM buku_tamu";
$result = mysqli_query($conn, $sql);

echo "<h1>Daftar Buku Tamu</h1>";
echo "<table border='1'>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Pesan</th>
        </tr>";
$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>$no</td>
            <td>{$row['nama']}</td>
            <td>{$row['email']}</td>
            <td>{$row['pesan']}</td>
          </tr>";
    $no++;
}
echo "</table>";

mysqli_close($conn);
?>

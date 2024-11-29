<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$nama = $email = $pesan = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars(trim($_POST['nama'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $pesan = htmlspecialchars(trim($_POST['pesan'] ?? ''));

    if (empty($nama) || empty($email) || empty($pesan)) {
        echo "<p style='color: red;'>Semua kolom harus diisi!</p>";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p style='color: red;'>Format email tidak valid!</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO buku_tamu (nama, email, pesan) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $email, $pesan);

            if ($stmt->execute()) {
                echo "<p style='color: green;'>Data berhasil disimpan!</p>";
            } else {
                echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
            }
            
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buku Tamu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h1, h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        input, textarea {
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }

        th, td {
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Form Buku Tamu</h1>
        <form method="POST" action="">
            <label for="nama">Nama:</label>
            <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($nama); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="pesan">Pesan:</label>
            <textarea name="pesan" id="pesan" required><?php echo htmlspecialchars($pesan); ?></textarea>

            <button type="submit">Kirim</button>
        </form>

        <h2>Daftar Buku Tamu</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
            </tr>

            <?php
            $sql = "SELECT * FROM buku_tamu";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['nama'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['email'] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row['pesan'] ?? '') . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Tidak ada data tamu.</td></tr>";
            }

            mysqli_close($conn);
            ?>
        </table>
    </div>
</body>
</html>
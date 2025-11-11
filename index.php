<?php 
// Memulai session untuk menyimpan data pengguna
session_start();

// Mengambil parameter error dari URL (jika ada), default kosong
$error = $_GET['err'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS untuk styling responsive -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 350px">
            <div class="card-body">
                <h4 class="text-center mb-3">Login</h4>
                
                <!-- Menampilkan pesan error jika ada -->
                <?php if ($error) : ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>

                <!-- Form login yang mengirim data ke proses_login.php -->
                <form action="proses_login.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <!-- Input username dengan autofocus -->
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <!-- CATATAN: Gunakan type="password" agar input tersembunyi -->
                        <input type="text" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

            </div>
        </div>
    </div>
</body>
</html>
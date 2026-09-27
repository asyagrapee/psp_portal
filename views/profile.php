<?php
// portal_psp/views/profile.php

// Memanggil controller untuk dapatkan data profil
require_once __DIR__ . '/../controllers/ProfileController.php';

$controller = new ProfileController();
$profile = $controller->getProfile();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Student Portal - Profil Saya</title>
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; color: #333; }
        
        /* Bar Navigasi */
        .navbar { background-color: #1e293b; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; color: #38bdf8; text-decoration: none; font-size: 1.2rem; }
        .navbar-text { font-size: 0.95rem; color: #cbd5e1; }
        .navbar-text strong { color: white; }

        /* Susunan Kad Utama */
        .container { display: flex; justify-content: center; align-items: center; min-height: 80vh; padding: 20px; }
        .card { background: #ffffff; width: 100%; max-width: 550px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); overflow: hidden; }
        
        /* Kepala Kad (Header) */
        .card-header { background: linear-gradient(135deg, #0284c7, #0369a1); color: white; padding: 25px; text-align: center; }
        .card-header h4 { font-size: 1.4rem; font-weight: 600; letter-spacing: 0.5px; }
        
        /* Badan Kad (Body) */
        .card-body { padding: 30px; }
        .info-group { display: flex; padding: 15px 0; border-bottom: 1px solid #f1f5f9; align-items: center; }
        .info-group:last-child { border-bottom: none; }
        .info-label { width: 35%; font-weight: 600; color: #64748b; font-size: 0.95rem; }
        .info-value { width: 65%; font-weight: 600; color: #0f172a; font-size: 1rem; }
        .text-primary-custom { color: #0284c7; font-weight: 700; }

        /* Kaki Kad & Butang (Footer) */
        .card-footer { background-color: #f8fafc; padding: 20px 30px; display: flex; justify-content: space-between; border-top: 1px solid #e2e8f0; }
        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; cursor: pointer; }
        .btn-password { background-color: #eab308; color: #1e293b; box-shadow: 0 4px 6px rgba(234,179,8,0.2); }
        .btn-password:hover { background-color: #ca8a04; }
        .btn-logout { background-color: #ef4444; color: white; box-shadow: 0 4px 6px rgba(239,68,68,0.2); border: none; }
        .btn-logout:hover { background-color: #dc2626; }
        .btn-grade {
    background-color: #2563eb;
    color: white;
    box-shadow: 0 4px 6px rgba(37,99,235,0.2);
}

.btn-grade:hover {
    background-color: #1d4ed8;
}

        /* Gambar Profil */
        .profile-pic-wrap { text-align: center; padding: 25px 0 0; }
        .profile-pic { width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; background: #f1f5f9; }
        .upload-form { display: flex; flex-direction: column; align-items: center; gap: 10px; margin-top: 12px; }
        .upload-form input[type="file"] { font-size: 0.8rem; }
        .btn-upload { background-color: #0284c7; color: white; border: none; padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer; }
        .btn-upload:hover { background-color: #0369a1; }
        .alert { padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 500; margin: 0 30px; margin-top: 15px; }
        .alert-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    </style>
</head>
<body>

    <!-- Bar Navigasi Atas -->
    <nav class="navbar">
        <a class="navbar-brand" href="#">PSP STUDENT PORTAL</a>
        <div class="navbar-text">
            Log Masuk: <strong><?php echo htmlspecialchars($profile['name']); ?></strong>
        </div>
    </nav>

    <!-- Kandungan Utama Kad Profil -->
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>Maklumat Profil Pelajar</h4>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="profile-pic-wrap">
                <?php
                    $pic = $profile['profile_picture'] ?? null;
                    $picSrc = $pic ? "../uploads/profile/" . htmlspecialchars($pic) : "https://via.placeholder.com/110?text=No+Photo";
                ?>
                <img src="<?php echo $picSrc; ?>" alt="Gambar Profil" class="profile-pic">

                <form class="upload-form" action="../controllers/ProfileController.php?action=uploadPicture" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_picture" required>
                    <button type="submit" class="btn-upload">📷 Kemaskini Gambar</button>
                </form>
            </div>

            <div class="card-body">
                <div class="info-group">
                    <div class="info-label">Nama Penuh</div>
                    <div class="info-value"><?php echo htmlspecialchars($profile['name']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">No. KP (NRIC)</div>
                    <div class="info-value"><?php echo htmlspecialchars($profile['nric']); ?></div>
                </div>
                <div class="info-group">
                    <div class="info-label">Program / Kursus</div>
                    <div class="info-value text-primary-custom"><?php echo htmlspecialchars($profile['program']); ?></div>
                </div>
            </div>
    <div class="card-footer">

    <a href="change_password.php" class="btn btn-password">
        🔐 Tukar Kata Laluan
    </a>

   <a href="../grade_management.php" class="btn btn-grade">
    📊 Grade Management
</a>

    <a href="logout.php" class="btn btn-logout">
        🚪 Log Keluar
    </a>

</div>
        </div>
    </div>

</body>
</html>
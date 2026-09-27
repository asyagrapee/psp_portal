<?php
// portal_psp/views/change_password.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Student Portal - Tukar Kata Laluan</title>
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; color: #333; }
        
        .navbar { background-color: #1e293b; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; color: #38bdf8; text-decoration: none; font-size: 1.2rem; }
        
        .container { display: flex; justify-content: center; align-items: center; min-height: 80vh; padding: 20px; }
        .card { background: #ffffff; width: 100%; max-width: 500px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.08); overflow: hidden; }
        
        .card-header { background: linear-gradient(135deg, #0284c7, #0369a1); color: white; padding: 25px; text-align: center; }
        .card-header h4 { font-size: 1.4rem; font-weight: 600; letter-spacing: 0.5px; }
        
        .card-body { padding: 30px; }
        
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-weight: 600; color: #475569; margin-bottom: 8px; font-size: 0.95rem; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; transition: border-color 0.2s; }
        .form-control:focus { outline: none; border-color: #0284c7; }
        
        /* Alert Message Styling */
        .alert { padding: 12px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem; font-weight: 500; }
        .alert-danger { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        
        .btn { padding: 10px 20px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s; cursor: pointer; border: none; }
        .btn-submit { background-color: #0284c7; color: white; box-shadow: 0 4px 6px rgba(2,132,199,0.2); }
        .btn-submit:hover { background-color: #0369a1; }
        .btn-back { background-color: #64748b; color: white; }
        .btn-back:hover { background-color: #475569; }
    </style>
</head>
<body>

    <nav class="navbar">
        <a class="navbar-brand" href="#">PSP STUDENT PORTAL</a>
    </nav>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>🔒 Penukaran Kata Laluan</h4>
            </div>
            <div class="card-body">

                <!-- Papar Ralat Sekiranya Ada -->
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Papar Mesej Berjaya Sekiranya Ada -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Borang Pertukaran Kata Laluan (Part 1.2) -->
                <form action="../controllers/ProfileController.php?action=changePassword" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="old_password">Kata Laluan Lama (Old Password)</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new_password">Kata Laluan Baharu (New Password)</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Sahkan Kata Laluan Baharu (Confirm Password)</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 25px;">
                        <a href="profile.php" class="btn btn-back">⬅ Kembali ke Profil</a>
                        <button type="submit" class="btn btn-submit">Simpan Kata Laluan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>
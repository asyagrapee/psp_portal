<?php
// portal_psp/login.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Jika pelajar sudah log masuk, terus hantar ke profil
if (isset($_SESSION['nric'])) {
    header("Location: views/profile.php");
    exit();
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/student.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nric = trim($_POST['nric']);
    $password = trim($_POST['password']);

    if (empty($nric) || empty($password)) {
        $error = "Sila isi Nombor KP dan Kata Laluan!";
    } else {
        global $conn;

        // Table 'students' sahaja - tiada table 'users' dalam sistem ini
        $sql = "SELECT id, nric, name, program, password FROM students WHERE nric = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $student = $result->fetch_assoc();
            $db_password = $student['password'];

            // Sokong BCRYPT hashing & teks biasa (fallback)
            if (password_verify($password, $db_password) || $password === $db_password) {

                // Simpan maklumat pelajar ke dalam session
                $_SESSION['student_id'] = $student['id'];
                $_SESSION['nric'] = $student['nric'];
                $_SESSION['name'] = $student['name'];

                header("Location: views/profile.php");
                exit();

            } else {
                $error = "Kata laluan salah!";
            }

        } else {
            $error = "Nombor KP tidak ditemui dalam sistem!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSP Student Portal - Log Masuk</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f3f4f6; font-family: 'Segoe UI', Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; width: 100%; max-width: 400px; padding: 40px 30px; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); text-align: center; }
        .logo-title { font-size: 1.5rem; font-weight: bold; color: #0284c7; margin-bottom: 5px; }
        .subtitle { font-size: 0.9rem; color: #64748b; margin-bottom: 25px; }
        .form-group { text-align: left; margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 8px; }
        .form-control { width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; background-color: #f8fafc; }
        .btn-login { width: 100%; padding: 12px; background: linear-gradient(135deg, #0284c7, #0369a1); color: white; border: none; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 10px; box-shadow: 0 4px 6px rgba(2,132,199,0.2); }
        .btn-login:hover { background: #0369a1; }
        .alert-error { background-color: #fee2e2; color: #ef4444; padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px; font-weight: 600; text-align: left; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-title">PSP STUDENT PORTAL</div>
        <div class="subtitle">Sila log masuk untuk mengakses portal pelajar</div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>Nombor Kad Pengenalan (NRIC)</label>
                <input type="text" name="nric" class="form-control" placeholder="Contoh: 040506071234" required>
            </div>
            <div class="form-group">
                <label>Kata Laluan</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan kata laluan" required>
            </div>
            <button type="submit" class="btn-login">Log Masuk</button>
        </form>
    </div>

</body>
</html>
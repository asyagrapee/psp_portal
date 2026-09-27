<?php
// portal_psp/controllers/ProfileController.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ProfileController {
    private $conn;

    public function __construct() {
        // Sambungan ke Database XAMPP local (password kosong)
        $this->conn = new mysqli("localhost", "root", "", "psp_portal");

        if ($this->conn->connect_error) {
            die("Sambungan pangkalan data gagal: " . $this->conn->connect_error);
        }
    }

    // FUNGSI 1: Dapatkan Data Profil Pelajar (Person 4)
    public function getProfile() {
        $nric = $_SESSION['nric'] ?? '040506071234'; // Default NRIC jika tiada session
        
        $stmt = $this->conn->prepare("SELECT name, nric, program, profile_picture FROM students WHERE nric = ?");
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = $result->fetch_assoc();

        // Mengembalikan data pelajar atau fallback jika rekod tiada
        return $data ?? [
            'name' => 'Muhammad Ali',
            'nric' => '040506071234',
            'program' => 'DIT (Full Stack)',
            'profile_picture' => null
        ];
    }

    // =========================================================================
    // FUNGSI 4: Upload Gambar Profil (Person 1, Part 1.1)
    // =========================================================================
    public function uploadPicture() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profile_picture'])) {
            header("Location: ../views/profile.php");
            exit();
        }

        $nric = $_SESSION['nric'] ?? null;
        if (!$nric) {
            $_SESSION['error'] = "Sesi tamat, sila log masuk semula.";
            header("Location: ../login.php");
            exit();
        }

        $file = $_FILES['profile_picture'];

        // 1. Semak error upload asas
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Ralat semasa upload (kod: " . $file['error'] . ").";
            header("Location: ../views/profile.php");
            exit();
        }

        // 2. Validation: File Type (.jpg, .jpeg, .png sahaja) - semak MIME sebenar
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMime = ['image/jpeg', 'image/png'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png'];

        if (!in_array($mimeType, $allowedMime) || !in_array($ext, $allowedExt)) {
            $_SESSION['error'] = "Jenis fail tidak sah. Hanya .jpg, .jpeg, .png dibenarkan.";
            header("Location: ../views/profile.php");
            exit();
        }

        // 3. Validation: File Size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = "Saiz fail terlalu besar. Maksimum 2MB.";
            header("Location: ../views/profile.php");
            exit();
        }

        // 4. Secure renaming guna uniqid()
        $newFileName = uniqid('profile_', true) . '.' . $ext;
        $uploadDir = __DIR__ . '/../uploads/profile/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
            $_SESSION['error'] = "Gagal memindahkan fail ke folder upload.";
            header("Location: ../views/profile.php");
            exit();
        }

        // 5. Padam gambar lama (kalau ada) supaya tak bertimbun
        $stmt = $this->conn->prepare("SELECT profile_picture FROM students WHERE nric = ?");
        $stmt->bind_param("s", $nric);
        $stmt->execute();
        $old = $stmt->get_result()->fetch_assoc();
        if ($old && !empty($old['profile_picture']) && file_exists($uploadDir . $old['profile_picture'])) {
            unlink($uploadDir . $old['profile_picture']);
        }

        // 6. Update database - filename disimpan, dikaitkan dengan session user (nric) aktif
        $updateStmt = $this->conn->prepare("UPDATE students SET profile_picture = ? WHERE nric = ?");
        $updateStmt->bind_param("ss", $newFileName, $nric);

        if ($updateStmt->execute()) {
            $_SESSION['success'] = "Gambar profil berjaya dikemaskini!";
        } else {
            $_SESSION['error'] = "Fail berjaya upload tetapi gagal disimpan dalam pangkalan data.";
        }

        header("Location: ../views/profile.php");
        exit();
    }

    // FUNGSI 2: Tukar Kata Laluan (Person 3)
    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nric = $_SESSION['nric'] ?? '040506071234';
            $old_password = $_POST['old_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Semak padanan kata laluan baharu
            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = "Pengesahan kata laluan baharu tidak padan!";
                header("Location: ../views/change_password.php");
                exit();
            }

            // Ambil data akaun dari database
            $stmt = $this->conn->prepare("SELECT password FROM students WHERE nric = ?");
            $stmt->bind_param("s", $nric);
            $stmt->execute();
            $result = $stmt->get_result();
            $student = $result->fetch_assoc();

            if (!$student) {
                $_SESSION['error'] = "Pengguna tidak ditemui!";
                header("Location: ../views/change_password.php");
                exit();
            }

            // Semak kata laluan lama
            $db_password = $student['password'];
            $is_valid_old = password_verify($old_password, $db_password) || ($old_password === $db_password);

            if (!$is_valid_old) {
                $_SESSION['error'] = "Kata laluan lama tidak tepat!";
                header("Location: ../views/change_password.php");
                exit();
            }

            // Enkripsi kata laluan baharu
            $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

            // Kemaskini dalam pangkalan data
            $updateStmt = $this->conn->prepare("UPDATE students SET password = ? WHERE nric = ?");
            $updateStmt->bind_param("ss", $hashed_password, $nric);

            if ($updateStmt->execute()) {
                $_SESSION['success'] = "Kata laluan berjaya dikemaskini!";
                header("Location: ../views/change_password.php");
                exit();
            } else {
                $_SESSION['error'] = "Gagal mengemaskini kata laluan.";
                header("Location: ../views/change_password.php");
                exit();
            }
        }
    }

    // FUNGSI 3: Log Keluar Sesi (Person 4)
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../views/login.php");
        exit();
    }
}

// Router untuk mengendalikan tindakan dari URL / Form
if (isset($_GET['action'])) {
    $controller = new ProfileController();
    if ($_GET['action'] === 'changePassword') {
        $controller->changePassword();
    } elseif ($_GET['action'] === 'logout') {
        $controller->logout();
    } elseif ($_GET['action'] === 'uploadPicture') {
        $controller->uploadPicture();
    }
}
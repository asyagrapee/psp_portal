<?php

require_once __DIR__ . '/../config/database.php';

class User
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Get user by username
    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Get student profile
    public function getStudentProfile($user_id)
    {
        $sql = "SELECT users.user_id,
                       users.student_id,
                       users.username,
                       students.name,
                       students.nric,
                       students.program
                FROM users
                INNER JOIN students
                    ON users.student_id = students.student_id
                WHERE users.user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Verify old password
    public function verifyOldPassword($user_id, $oldPassword)
    {
        $sql = "SELECT password FROM users WHERE user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return false;
        }

        $row = $result->fetch_assoc();

        return password_verify($oldPassword, $row['password']);
    }

    // Update password
    public function updatePassword($user_id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = "UPDATE users
                SET password = ?
                WHERE user_id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $user_id);

        return $stmt->execute();
    }
}

?>
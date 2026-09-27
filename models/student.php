<?php

require_once __DIR__ . '/../config/database.php';

class Student
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Get all students
    public function getAllStudents()
    {
        $sql = "SELECT * FROM students";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("SQL Error: " . $this->conn->error);
        }

        return $result;
    }

    // Add student
    public function addStudent($name, $nric, $program, $marks)
    {
        $sql = "INSERT INTO students (name, nric, program, marks)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssd", $name, $nric, $program, $marks);

        return $stmt->execute();
    }

    // Get student by NRIC
    public function getStudentByNric($nric)
    {
        $sql = "SELECT * FROM students WHERE nric = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nric);
        $stmt->execute();

        return $stmt->get_result();
    }

    // Update student
    public function updateStudent($name, $nric, $program, $marks)
    {
        $sql = "UPDATE students
                SET name = ?, program = ?, marks = ?
                WHERE nric = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $program, $marks, $nric);

        return $stmt->execute();
    }

    // Delete student
    public function deleteStudent($nric)
    {
        $sql = "DELETE FROM students WHERE nric = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nric);

        return $stmt->execute();
    }

    // =========================================================================
    // TUGAS PERSON 2 (PART 1.1): Ambil data profil pelajar berdasarkan ID Sesi
    // =========================================================================
    public function getStudentById($student_id)
    {
        // Menggunakan Prepared Statement untuk keselamatan SQL Injection (Rubrik)
        $sql = "SELECT name, nric, program FROM students WHERE id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}

?>

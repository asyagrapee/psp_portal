<?php

require_once __DIR__ . '/../config/database2.php';

class Student2
{
    private $conn;

    public function __construct()
    {
        global $conn2;
        $this->conn = $conn2;
    }

    public function getAllStudents()
    {
        $stmt = $this->conn->prepare("SELECT * FROM students");
        $stmt->execute();

        return $stmt->get_result();
    }

    public function getStudentById($id)
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM students WHERE id = ?"
        );

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function createStudent($name, $ic, $marks)
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO students(name, ic, marks)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "ssi",
            $name,
            $ic,
            $marks
        );

        return $stmt->execute();
    }

    public function updateStudent($id, $name, $ic, $marks)
    {
        $stmt = $this->conn->prepare(
            "UPDATE students
             SET name = ?, ic = ?, marks = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssii",
            $name,
            $ic,
            $marks,
            $id
        );

        return $stmt->execute();
    }

    public function deleteStudent($id)
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM students WHERE id = ?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}

?>
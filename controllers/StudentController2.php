<?php

require_once __DIR__ . '/../models/Student2.php';

class StudentController2
{
    private $student;

    public function __construct()
    {
        $this->student = new Student2();
    }

    public function index()
    {
        $students = $this->student->getAllStudents();
        include __DIR__ . '/../views/student_list2.php';
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'];
            $ic = $_POST['ic'];
            $marks = $_POST['marks'];

            $this->student->createStudent(
                $name,
                $ic,
                $marks
            );

            header("Location: grade_management.php");
            exit;
        }

        include __DIR__ . '/../views/add_student2.php';
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'];
            $ic = $_POST['ic'];
            $marks = $_POST['marks'];

            $this->student->updateStudent(
                $id,
                $name,
                $ic,
                $marks
            );

            header("Location: grade_management.php");
            exit;
        }

        $student = $this->student->getStudentById($id);

        include __DIR__ . '/../views/edit_student2.php';
    }

    public function delete($id)
    {
        $this->student->deleteStudent($id);

        header("Location: grade_management.php");
        exit;
    }
}

?>
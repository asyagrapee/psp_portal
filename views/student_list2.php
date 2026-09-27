<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

   <div class="d-flex justify-content-between align-items-center mb-3">

    <h2>Grade Management System</h2>

    <div>
        <a href="views/profile.php"
           class="btn btn-secondary">
           ← Back to Profile
        </a>

        <a href="grade_management.php?action=create"
           class="btn btn-primary">
           Add Student
        </a>
    </div>

</div>

    <table class="table table-bordered">

        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>IC</th>
            <th>Marks</th>
            <th>Action</th>
        </tr>

        <?php while($row = $students->fetch_assoc()) { ?>

        <tr>

            <td><?= $row['id']; ?></td>
            <td><?= $row['name']; ?></td>
            <td><?= $row['ic']; ?></td>
            <td><?= $row['marks']; ?></td>

            <td>

                <a href="grade_management.php?action=edit&id=<?= $row['id']; ?>"
                   class="btn btn-warning btn-sm">
                   Edit
                </a>

                <a href="grade_management.php?action=delete&id=<?= $row['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this student?')">
                   Delete
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>
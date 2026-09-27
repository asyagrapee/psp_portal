<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

    <h2>Add Student</h2>

    <form method="POST">

        <div class="mb-3">
            <label>Name</label>

            <input type="text"
                   name="name"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>IC</label>

            <input type="text"
                   name="ic"
                   class="form-control"
                   required>
        </div>

        <div class="mb-3">
            <label>Marks</label>

            <input type="number"
                   name="marks"
                   class="form-control"
                   required>
        </div>

        <button type="submit"
                class="btn btn-success">
                Save
        </button>

        <a href="grade_management.php"
           class="btn btn-secondary">
           Back
        </a>

    </form>

</div>

</body>
</html>
<?php

include ('../config.php');
if (!isset($conn) || !$conn) {
    die("Database Connection Error: " . mysqli_connect_error());
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Student ID is required.");
}

$query = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("No student found with ID: " . $id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Website</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="homePage.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="viewAllStudents.html">View All Students</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Update Student</h2>
        <form id="updateStudentForm">
            <input type="hidden" id="studentsId" name="id" value="<?= $student['id']; ?>">

            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $student['firstName']; ?>">
            </div>

            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $student['lastName']; ?>">
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?= $student['phone']; ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $student['email']; ?>">
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
    $("#updateStudentForm").submit(function (event) {
    event.preventDefault();  // Form refresh na ho isliye

    let studentData = {
        id: $("#studentsId").val(),
        firstname: $("#firstname").val(),
        lastname: $("#lastname").val(),
        phone: $("#phone").val(),
        email: $("#email").val()
    };

    console.log("Sending Data:", studentData);  // Debug ke liye

    $.ajax({
        url: "http://localhost/phpApi/updateApi.php",  // Ensure file name is correct
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(studentData),  // JSON me send karna hai
        success: function (response) {
            console.log(response);
            if (response.success) {
                alert(response.success);
                window.location.href = "viewAllStudents.html";
            } else {
                alert(response.error);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error updating student:", error);
            alert("Failed to update student! Check console for details.");
        }
    });
});
$(document).ready(function () {
    $("#updateStudentForm").submit(function (event) {
        event.preventDefault(); // Form submit hone se rokna

        let studentId = $("#studentsId").val();
        let firstname = $("#firstname").val();
        let lastname = $("#lastname").val();
        let phone = $("#phone").val();
        let email = $("#email").val();

        console.log("Student ID:", studentId);
        console.log("First Name:", firstname);
        console.log("Last Name:", lastname);
        console.log("Phone:", phone);
        console.log("Email:", email);

        if (!studentId || !firstname || !lastname || !phone || !email) {
            alert("All fields are required!");
            return;
        }

        let studentData = {
            firstname: firstname,
            lastname: lastname,
            phone: phone,
            email: email
        };

        console.log("Sending Data:", studentData); // Debugging ke liye

        $.ajax({
            url: "http://localhost/phpApi/updateApi.php?id=" + studentId,
            type: "PUT", // ✅ PUT method use kar rahe hain
            contentType: "application/json",
            data: JSON.stringify(studentData), // JSON format me send kar rahe hain
            success: function (response) {
                console.log("API Response:", response);
                
                // ✅ Check karo ki response object sahi hai ya nahi
                if (response && response.success) {
                    alert(response.success);
                    window.location.href = "viewAllStudents.html"; // Redirect after update
                } else if (response && response.error) {
                    alert(response.error);
                } else {
                    alert("Unexpected response from server!");
                    console.log(response);
                }
            },
            error: function (xhr, status, error) {
                console.error("Error updating student:", xhr.responseText);
                alert("Failed to update student! Check console for details.");
            }
        });
    });
});


</script>
</body>
</html>

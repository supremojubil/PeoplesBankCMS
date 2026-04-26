<?php
// Start the session
session_start();

// Include the database connection file
require_once "db.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];


    try {
        // Prepare an SQL statement to insert new account information into a table
        $sql = "INSERT INTO tblusers (username, password) VALUES (:username, :password)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute the statement
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // Set session variable for account creation success
        $_SESSION['account_created'] = true;

        // Redirect to create_account.php
        header("Location: ../register.php");
        exit();
    } catch (PDOException $e) {
        // If an error occurs during database operation, catch the exception and handle it
        echo "Error: " . $e->getMessage();
        // You can redirect to an error page or display an error message
    }
} else {
    // If the form is not submitted, redirect back to the create account page
    header("Location: ../register.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert</title>
</head>

<body>
    <script>
        // Display a JavaScript alert notification
        alert("Account created successfully. You can now login.");
        // Redirect to the login page after a delay (e.g., 2 seconds)
        setTimeout(function() {
            window.location.href = "../login.php";
        }, 2000); // 2000 milliseconds = 2 seconds
    </script>
</body>

</html>
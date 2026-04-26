<?php
session_start();
require_once "connection.php"; // adjust path if needed

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Check empty fields
    if (empty($username) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }

    try {
        // Query user from database
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Since passwords are plain text
            if ($password === $user['password']) {
                // ✅ Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Redirect to dashboard or home page
                header("Location: ../index.php"); // create this page later
                exit();
            } else {
                // Password incorrect
                header("Location: login.php?error=wrong_password");
                exit();
            }
        } else {
            // Username not found
            header("Location: login.php?error=not_found");
            exit();
        }

    } catch (PDOException $e) {
        die("Database Error: " . $e->getMessage());
    }

} else {
    // If not POST, redirect back to login
    header("Location: login.php");
    exit();
}

<?php
$host = "localhost";
$port = 3309;
$dbname = "cms_db";
$user = "adminserver";
$pass = "admin123!@#";

try {
   $pdo = new PDO(
      "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
      $user,
      $pass,
      [
         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]
   );
} catch (PDOException $e) {
   die("Connection failed: " . $e->getMessage());
}

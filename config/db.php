<?php
/*
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
// Line ena e ka holimo e na le error nyana,, research on it.

// config/db.php
$host = 'localhost';
$dbname = 'VetManagement';
$username = 'root'; // Default XAMPP username
$password = 'N#!!)n!gr0';     // Default XAMPP password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
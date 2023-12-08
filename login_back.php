<?php
require 'vendor/autoload.php'; // include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Get username and password from the request
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

// Specify the database and collection
$collectionName = 'inventory.admin';

// MongoDB query to find a document that matches the username and password
$filter = [
    'username' => $username,
    'password' => $password,
];

$query = new MongoDB\Driver\Query($filter);
$cursor = $manager->executeQuery($collectionName, $query);

$document = current($cursor->toArray());

if ($document) {
    // Authentication successful
    session_start();
    $_SESSION['username'] = $username;
    echo "<script>alert('Login Successful')</script>";
    echo '<script>window.location.href = "user_dashboard.php";</script>';
    die();
} else {
    // Authentication failed
    echo "<script>alert('Wrong username or password')</script>";
    echo '<script>window.location.href = "index.php";</script>';
    die();

    
}
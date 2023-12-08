<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database
$databaseName = 'inventory';

// Get the ID to delete from the URL parameter
$id = $_REQUEST['id'];

// Create a filter to specify the document to delete
$filter = ['_id' => new MongoDB\BSON\ObjectId($id)];

// Create a delete command
$command = new MongoDB\Driver\BulkWrite;
$command->delete($filter);

try {
    $manager->executeBulkWrite("$databaseName.transact", $command);
    echo "<script>alert('Data deleted')</script>";
    echo "<script>window.location.href = 'transact.php';</script>";
} catch (Exception $e) {
    echo "ERROR: Could not delete the document: " . $e->getMessage();
}
?>

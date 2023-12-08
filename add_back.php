<?php
require 'vendor/autoload.php'; // include Composer's autoloader

// Manager Class

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database and collection
$databaseName = 'inventory';
$collectionName = 'product';

// Get data from the request
$prd_name = $_REQUEST['prd_name'];
$prd_disc = $_REQUEST['prd_disc'];
$prd_code = $_REQUEST['prd_code'];
$prd_cost = $_REQUEST['prd_cost'];
$prd_inventory = 0;

// Find the last used "id" value and increment it
$lastIdFilter = [];
$lastIdQuery = new MongoDB\Driver\Query($lastIdFilter, ['sort' => ['id' => -1], 'limit' => 1]);
$lastIdCursor = $manager->executeQuery("$databaseName.$collectionName", $lastIdQuery);

$lastId = 1; // Default value if no documents are found

foreach ($lastIdCursor as $document) {
    $lastId = $document->id + 1;
}

// Insert a new document with the auto-incremented "id"
$document = [
    'id' => $lastId,
    'prd_name' => $prd_name,
    'prd_disc' => $prd_disc,
    'prd_code' => $prd_code,
    'prd_cost' => $prd_cost,
    'prd_inventory' => $prd_inventory,
];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($document);

try {
    $manager->executeBulkWrite("$databaseName.$collectionName", $bulk);
    echo "<script>alert('Data added')</script>";
    echo '<script>window.location.href = "user_dashboard.php";</script>';
} catch (Exception $e) {
    echo "ERROR: Could not execute the MongoDB insert operation. " . $e->getMessage();
}
?>

<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database
$databaseName = 'inventory';

// Check if the user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>window location href='../index.php';</script>";
    exit();
}

// Get the product code from the request
$prd_code = $_REQUEST["id"];

// Function to get the next transaction ID
function getNextTransactionId($manager, $databaseName) {
    $filter = [];
    $options = ['sort' => ['id' => -1], 'limit' => 1];
    $query = new MongoDB\Driver\Query($filter, $options);

    $cursor = $manager->executeQuery("$databaseName.transact", $query);

    foreach ($cursor as $document) {
        return $document->id + 1;
    }

    return 1; // Start with 1 if no documents exist
}

// Function to update product inventory
function updateProductInventory($manager, $databaseName, $prd_code) {
    // Get the current product inventory as an integer
    $query = new MongoDB\Driver\Query(['prd_code' => $prd_code]);
    $cursor = $manager->executeQuery("$databaseName.product", $query);

    foreach ($cursor as $document) {
        $prd_inventory = (int)$document->prd_inventory;
        $prd_name = $document->prd_name;



        // Decrement the product inventory
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['prd_code' => $prd_code],
            ['$set' => ['prd_inventory' => $prd_inventory + 1]]
        );
        $manager->executeBulkWrite("$databaseName.product", $bulk);

        // Insert transaction record
        $transactionData = [
            'id' => getNextTransactionId($manager, $databaseName),
            'time' => time(),
            'prd_code' => $prd_code,
            'state' => 'Added',
            'prd_name' => $prd_name,
            'prd_quantity' => 1
        ];

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($transactionData);
        $manager->executeBulkWrite("$databaseName.transact", $bulk);

        echo "<script> alert('One Item Added') </script>";
        echo '<script>window.location.href = "user_dashboard.php";</script>';
        exit();
    }

    echo "Product not found or an error occurred.";
}

// Update the product inventory and insert the transaction record
updateProductInventory($manager, $databaseName, $prd_code);
?>

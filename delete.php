<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database
$databaseName = 'inventory';

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

// Function to delete a product and insert a transaction record
function deleteProductAndInsertTransaction($manager, $databaseName, $prd_code) {
    // Get product details before deletion
    $filter = ['prd_code' => $prd_code];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery("$databaseName.product", $query);

    foreach ($cursor as $document) {
        $prd_name = $document->prd_name;
        $prd_quantity = $document->prd_inventory;

        // Delete the product
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['prd_code' => $prd_code]);

        $manager->executeBulkWrite("$databaseName.product", $bulk);

        // Insert transaction record
        $transactionData = [
            'id' => getNextTransactionId($manager, $databaseName),
            'time' => time(),
            'prd_code' => $prd_code,
            'state' => 'Deleted',
            'prd_name' => $prd_name,
            'prd_quantity' => $prd_quantity
        ];

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->insert($transactionData);
        $manager->executeBulkWrite("$databaseName.transact", $bulk);

        echo "<script> alert('Product deleted') </script>";
        echo '<script>window.location.href = "user_dashboard.php";</script>';
        exit();
    }

    echo "Product not found or an error occurred.";
}

// Delete the product and insert the transaction record
deleteProductAndInsertTransaction($manager, $databaseName, $prd_code);
?>
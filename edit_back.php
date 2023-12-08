<?php
require 'vendor/autoload.php'; // Include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

// Specify the database
$databaseName = 'inventory';

// Function to get the next auto-incremented ID from the transact collection
function getNextAutoIncrementedID($manager, $databaseName) {
    $filter = [];
    $options = [
        'sort' => ['id' => -1],
        'limit' => 1,
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery("$databaseName.transact", $query);

    foreach ($cursor as $document) {
        return $document->id + 1;
    }

    return 1; // Start with 1 if no documents exist
}

// Generate the next auto-incremented ID
$nextID = getNextAutoIncrementedID($manager, $databaseName);

// Get other data from the request
$prd_code = $_REQUEST['id'];
$prd_name = $_REQUEST['prd_name'];
$prd_disc = $_REQUEST['prd_disc'];
$prd_cost = $_REQUEST['prd_cost'];
$prd_inventory = $_REQUEST['prd_inventory'];
$old_prd_inventory = $_REQUEST['old_prd_inventory'];

// Check and insert transaction record
$diff = abs($old_prd_inventory - $prd_inventory);
$time = time();
$state = $old_prd_inventory > $prd_inventory ? 'Removed' : 'Added';

$transactionData = [
    'id' => $nextID, // Auto-incremented ID
    'time' => $time,
    'prd_code' => $prd_code,
    'state' => $state,
    'prd_name' => $prd_name,
    'prd_quantity' => $diff
];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($transactionData);

$manager->executeBulkWrite("$databaseName.transact", $bulk);

// Now, update the product details
$filter = ['prd_code' => $prd_code];
$update = [
    '$set' => [
        'prd_name' => $prd_name,
        'prd_disc' => $prd_disc,
        'prd_cost' => $prd_cost,
        'prd_inventory' => $prd_inventory
    ]
];

$options = ['multi' => false];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update($filter, $update, $options);

$manager->executeBulkWrite("$databaseName.product", $bulk);

echo "<script>alert('Data Saved');</script>";
echo '<script>window.location.href = "user_dashboard.php";</script>';
?>

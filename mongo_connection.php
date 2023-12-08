<?php
require 'vendor/autoload.php'; // include Composer's autoloader

// Manager Class
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$collectionName = 'inventory.admin'; // Specify the database and collection

// Create Operation
$a = "3";
$dataToInsert = [
    'id' => $a,
    'username' => 'new_admin',
    'password' => '3',
];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($dataToInsert);

try {
    $manager->executeBulkWrite($collectionName, $bulk);
    echo "Document added successfully with _id: " . $dataToInsert['id'] . "\n";
} catch (Exception $e) {
    echo "Failed to insert the document: " . $e->getMessage() . "\n";
}

// Read Operation
$query = new MongoDB\Driver\Query(['id' => $a]);
$cursor = $manager->executeQuery($collectionName, $query);

foreach ($cursor as $document) {
    echo "Read document: " . json_encode($document) . "\n";
}

// Update Operation
$newUsername = 'updated_admin';
$newData = ['$set' => ['username' => $newUsername]];
$updateOptions = ['multi' => false, 'upsert' => false];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(['id' => $a], $newData, $updateOptions);

try {
    $manager->executeBulkWrite($collectionName, $bulk);
    echo "Document updated successfully\n";
} catch (Exception $e) {
    echo "Failed to update the document: " . $e->getMessage() . "\n";
}

// Delete Operation
$deleteOptions = ['limit' => 1]; // Delete only one matching document

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->delete(['id' => $a], $deleteOptions);

try {
    $manager->executeBulkWrite($collectionName, $bulk);
    echo "Document deleted successfully\n";
} catch (Exception $e) {
    echo "Failed to delete the document: " . $e->getMessage() . "\n";
}
?>

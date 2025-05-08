<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode the received JSON string
    $motorShops = json_decode($_POST['motorShops'], true);

    // Database connection details
    $host = 'localhost';
    $db = 'mshop_db';
    $user = 'root';
    $pass = '';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        echo "Connected successfully to the database.<br>"; // Debugging line
    } catch (\PDOException $e) {
        echo "Database connection failed: " . $e->getMessage();
        exit;
    }

    // Query to fetch name and description from product_list table
    $sqlFetch = "SELECT name, description FROM product_list";
    $stmtFetch = $pdo->query($sqlFetch);
    $productList = $stmtFetch->fetchAll();

    
    // Delete all records from chatbot table
    $sqlDeleteAll = "DELETE FROM chatbot";
    $stmtDeleteAll = $pdo->prepare($sqlDeleteAll);
    $stmtDeleteAll->execute();

    // Insert query
    $sqlInsert = "INSERT INTO chatbot (queries, replies) VALUES (:queries, :replies)";
    $stmtInsert = $pdo->prepare($sqlInsert);

    // Check query
    $sqlCheck = "SELECT COUNT(*) FROM chatbot WHERE queries = :queries";
    $stmtCheck = $pdo->prepare($sqlCheck);

    // Process motorShops data
    foreach ($motorShops as $shop) {
        $combinedDatas = "Nearest Malapit " . $shop['name'] . " " . $shop['details'];
        $combinedData = $shop['name'] . " " . $shop['details'];

        // Remove special characters from replies
        $cleanedReplies = preg_replace('/[^A-Za-z0-9\s\-\n:]/', '', $combinedData);


        // Check if the shop already exists
        $stmtCheck->execute(['queries' => nl2br($combinedData)]);
        $count = $stmtCheck->fetchColumn();

        if ($count == 0) {
            try {
                $stmtInsert->execute([
                    'queries' => nl2br($combinedDatas),
                    'replies' => nl2br($cleanedReplies) // Cleaned replies
                ]);
                echo "Inserted shop: " . $shop['name'] . "<br>";
            } catch (\PDOException $e) {
                echo "Error inserting shop: " . $shop['name'] . " - " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Shop already exists: " . $shop['name'] . "<br>";
        }
    }

    // Process product_list data
    foreach ($productList as $product) {
        $queries = $product['name'];

        // Remove special characters from replies
        $replies = preg_replace('/[^A-Za-z0-9\s\-\n]/', '', $product['description']);

        // Check if the product already exists
        $stmtCheck->execute(['queries' => nl2br($queries)]);
        $count = $stmtCheck->fetchColumn();

        if ($count == 0) {
            try {
                $stmtInsert->execute([
                    'queries' => nl2br($queries),
                    'replies' => nl2br($replies) // Cleaned replies
                ]);
                echo "Inserted product: " . $product['name'] . "<br>";
            } catch (\PDOException $e) {
                echo "Error inserting product: " . $product['name'] . " - " . $e->getMessage() . "<br>";
            }
        } else {
            echo "Product already exists: " . $product['name'] . "<br>";
        }
    }
}
?>

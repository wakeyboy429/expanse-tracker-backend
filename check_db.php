<?php
// DB check script in execution phase...
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=expanse_tracker_backend", "root", "");
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    file_put_contents('db_check.txt', implode("\n", $tables));
    echo "Done";
} catch (Exception $e) {
    file_put_contents('db_check.txt', "Error: " . $e->getMessage());
    echo "Error";
}

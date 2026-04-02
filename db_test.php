<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=expanse_tracker_backend', 'root', '');
    echo "Connected successfully\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

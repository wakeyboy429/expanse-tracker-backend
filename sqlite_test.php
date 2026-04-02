<?php
try {
    $db = new PDO('sqlite:database/database.sqlite');
    echo "Connected successfully to SQLite\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

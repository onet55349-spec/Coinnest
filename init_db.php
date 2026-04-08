<?php
require_once 'includes/db.php';

$sql = file_get_contents('database.sql');

try {
    // Drop existing tables to ensure a clean start
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE `$table` ");
    }
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    
    // Execute the SQL from database.sql
    $pdo->exec($sql);
    
    echo json_encode(['success' => true, 'message' => 'Database initialized successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>

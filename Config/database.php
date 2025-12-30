<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'Telur_Josjis');

// Create connection
function getConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

// Execute prepared statement (SELECT)
function executeQuery($sql, $params = [], $types = '') {
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    $stmt->close();
    $conn->close();
    
    return $data;
}

// Execute insert/update/delete
function executeUpdate($sql, $params = [], $types = '') {
    $conn = getConnection();
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $success = $stmt->execute();
    $affected_rows = $stmt->affected_rows;
    $insert_id = $conn->insert_id;
    
    $stmt->close();
    $conn->close();
    
    return [
        'success' => $success,
        'affected_rows' => $affected_rows,
        'insert_id' => $insert_id
    ];
}
?>

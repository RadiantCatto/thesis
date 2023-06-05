<?php
// Include the database configuration file
include_once 'database.php';

// Enable error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create a new database connection
$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("SELECT * FROM login WHERE datetime BETWEEN ? AND ? AND status = 'ok' ORDER BY datetime DESC LIMIT 1");
$stmt->bind_param('ss', $start, $end);
$start = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s')) - 20);
$end = date('Y-m-d H:i:s');
$stmt->execute();
$result = $stmt->get_result();

// Close the prepared statement and database connection
$stmt->close();
$conn->close();

// Process the query result
if ($result->num_rows <= 0) {
    $json['status'] = 'no login';
    $json['data'] = null;
    // Debug output
    echo "Missing data or empty result set\n";
    echo "Card ID: N/A\n";
} else {
    $row = $result->fetch_assoc();
    $json['status'] = 'ok';
    $json['data'] = $row;
}

// Set the JSON response header and output the result
header('Content-Type: application/json');
echo json_encode($json);
?>

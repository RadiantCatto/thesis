<?php
include_once 'database.php';

$conn = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cardID = $_GET['cardID']; // Change to $_GET for query parameters or $_POST for POST parameters
$earned = $_GET['points']; // Change to $_GET for query parameters or $_POST for POST parameters

if (!empty($cardID)) {
    $stmt = $conn->prepare("INSERT INTO login (datetime, cardID) VALUES (?, ?)");
    $stmt->bind_param('ss', $datetime, $cardID);
    $datetime = date('Y-m-d H:i:s');
    $stmt->execute();
    $stmt->close();

    // Retrieve values from the user_register table for the specific cardID
    $stmt = $conn->prepare("SELECT username, email, earned FROM user_register WHERE cardID = ?");
    $stmt->bind_param('s', $cardID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        $currentEarned = $row['earned'];

        // Update the earned field in the user_register table by adding the current earned points
        $updatedEarned = $currentEarned + $earned;
        $updateEarnedStmt = $conn->prepare("UPDATE user_register SET earned = ? WHERE cardID = ?");
        $updateEarnedStmt->bind_param('ds', $updatedEarned, $cardID);
        $updateEarnedStmt->execute();
        $updateEarnedStmt->close();

        // Check if there are existing records for the same cardID in the login table
        $existingStmt = $conn->prepare("SELECT * FROM login WHERE cardID = ?");
        $existingStmt->bind_param('s', $cardID);
        $existingStmt->execute();
        $existingResult = $existingStmt->get_result();

        if ($existingResult->num_rows > 0) {
            // Combine the values with existing records and update them
            while ($existingRow = $existingResult->fetch_assoc()) {
                $existingID = $existingRow['id'];
                $existingUsername = $existingRow['username'];
                $existingEmail = $existingRow['email'];
                $existingEarned = $existingRow['earned'];

                // Check if any existing values are NULL, and update them with the new values
                $combinedUsername = $existingUsername ?? $username;
                $combinedEmail = $existingEmail ?? $email;
                $combinedEarned = $existingEarned ?? $updatedEarned; // Use the updated earned value

                // Update the existing record with the combined values
                $updateStmt = $conn->prepare("UPDATE login SET username = ?, email = ?, earned = ? WHERE id = ?");
                $updateStmt->bind_param('ssdi', $combinedUsername, $combinedEmail, $combinedEarned, $existingID);
                $updateStmt->execute();
                $updateStmt->close();
            }
        } else {
            // Insert a new record into the login table
            $insertStmt = $conn->prepare("INSERT INTO login (cardID, username, email, earned) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param('sssd', $cardID, $username, $email, $updatedEarned); // Use the updated earned value
            $insertStmt->execute();
            $insertStmt->close();
        }

        $json['status'] = 'ok';
        $json['data'] = array(
            'cardID' => $cardID,
            'username' => $username,
            'email' => $email,
            'earned' => $updatedEarned // Use the updated earned value
        );
    } else {
        $json['status'] = 'no login';
        $json['data'] = array(
            'cardID' => $cardID,
            'username' => 'N/A',
            'email' => 'N/A',
            'earned' => 0
        );
    }
} else {
    $json['status'] = 'no cardID';
    $json['data'] = null;
}

header('Content-Type: application/json');
echo json_encode($json);

// Close the statement and connection
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>

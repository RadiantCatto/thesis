<?php
// Import security.php file
require('database/database.php');

// Function to check if email already exists in the database
function checkEmailExists($connection, $email) {
    $email_query = "SELECT * FROM user_register WHERE email=?";
    $email_query_run = $connection->prepare($email_query);
    $email_query_run->execute([$email]);

    return $email_query_run->rowCount() > 0;
}

// Handle registration form submission
if (isset($_POST['user_registerbtn'])) {
    // Retrieve form data submitted through POST method
    $username = $_POST['username']; // Updated from 'name' to 'username'
    $email = $_POST['email'];
    $cardID = $_POST['cardID'];
    $earned = $_POST['earned'];

    try {
        // Establish database connection
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if cardID already exists in the database
        $card_query = "SELECT * FROM user_register WHERE cardID=?";
        $card_query_run = $pdo->prepare($card_query);
        $card_query_run->execute([$cardID]);

        if ($card_query_run->rowCount() > 0) {
            // CardID already taken
            $_SESSION['status'] = "CardID Already Taken. Please Try Another one.";
            $_SESSION['status_code'] = "error";
            header('Location: profile_manage.php');
            exit();
        } else {
            // CardID not taken
            // Check if email already exists in the database
            $email_query = "SELECT * FROM user_register WHERE email=?";
            $email_query_run = $pdo->prepare($email_query);
            $email_query_run->execute([$email]);

            if ($email_query_run->rowCount() > 0) {
                // Email already taken
                $_SESSION['status'] = "Email Already Taken. Please Try Another one.";
                $_SESSION['status_code'] = "error";
                header('Location: profile_manage.php');
                exit();
            }

            // Insert data into the database
            $sql = "INSERT INTO `user_register` (username, cardID, email, earned) VALUES (?, ?, ?, ?)"; // Updated column names
            $q = $pdo->prepare($sql);
            $q->execute([$username, $cardID, $email, $earned]);

            // Registration successful
            $_SESSION['status'] = "User Profile Added";
            $_SESSION['status_code'] = "success";
            header('Location: profile_manage.php');
            exit();
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle update form submission
if (isset($_POST['user_updatebtn'])) {
    // Retrieve form data submitted through POST method
    $cardID = $_POST['edit_cardID'];
    $username = $_POST['edit_username']; // Updated from 'edit_name' to 'edit_username'
    $email = $_POST['edit_email'];
    $earned = $_POST['edit_earned'];

    try {
        // Establish database connection
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update data in the database
        $sql = "UPDATE user_register SET username=?, email=?, earned=? WHERE cardID=?";
        $q = $pdo->prepare($sql);
        $q->execute([$username, $email, $earned, $cardID]);

        // Update successful
        $_SESSION['status'] = "Your Data is Updated";
        $_SESSION['status_code'] = "success";
        header('Location: profile_manage.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

// Handle delete form submission
if (isset($_POST['user_delete_btn'])) {
    // Retrieve form data submitted through POST method
    $cardID = $_POST['delete_cardID'];

    try {
        // Establish database connection
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Delete data from the database
        $sql = "DELETE FROM user_register WHERE cardID=?";
        $q = $pdo->prepare($sql);
        $q->execute([$cardID]);

        // Deletion successful
        $_SESSION['status'] = "Your Data is Deleted";
        $_SESSION['status_code'] = "success";
        header('Location: profile_manage.php');
        exit();
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>

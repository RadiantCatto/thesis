<?php
    // Import security.php file
    include('security.php');

    // Handle registration form submission
    if (isset($_POST['user_registerbtn'])) {
        // Retrieve form data submitted through POST method
        $username = $_POST['username'];
        $email = $_POST['email'];
        $cardID = $_POST['cardID'];
        $earned = $_POST['earned'];

        // Check if email already exists in database
        $email_query = "SELECT * FROM user_register WHERE email='$email'";
        $email_query_run = mysqli_query($connection, $email_query);

        if (mysqli_num_rows($email_query_run) > 0) {
            // Email already taken
            $_SESSION['status'] = "Email Already Taken. Please Try Another one.";
            $_SESSION['status_code'] = "error";
            header('Location: profile_manage.php');
            exit();
        } else {
            // Email not taken
            // Insert data into database
            $query = "INSERT INTO user_register (username, email, cardID, earned) VALUES ('$username', '$email', '$cardID', '$earned')";
            $query_run = mysqli_query($connection, $query);

            if ($query_run) {
                // Registration successful
                $_SESSION['status'] = "User Profile Added";
                $_SESSION['status_code'] = "success";
                header('Location: profile_manage.php');
                exit();
            } else {
                // Registration failed
                $_SESSION['status'] = "User Profile Not Added";
                $_SESSION['status_code'] = "error";
                header('Location: profile_manage.php');
                exit();
            }
        }
    }


    // Handle update form submission
    if(isset($_POST['user_updatebtn'])) {
        // Retrieve form data submitted through POST method
        $user_id = $_POST['user_edit_id'];
        $username = $_POST['edit_username'];
        $email = $_POST['edit_email'];
        $cardID = $_POST['edit_cardID'];
        $earned = $_POST['edit_earned'];

        // Update data in database
        $query = "UPDATE user_register SET username='$username', email='$email', cardID='$cardID', earned='$earned' WHERE user_id='$user_id' ";
        $query_run = mysqli_query($connection, $query);

        if($query_run) {
            // Update successful
            $_SESSION['status'] = "Your Data is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: profile_manage.php'); 
        }
        else {
            // Update failed
            $_SESSION['status'] = "Your Data is NOT Updated";
            $_SESSION['status_code'] = "error";
            header('Location: profile_manage.php'); 
        }
    }


    // Handle delete form submission
    if(isset($_POST['user_delete_btn'])) {
        // Retrieve form data submitted through POST method
        $user_id = $_POST['user_delete_id'];

        // Delete data from database
        $query = "DELETE FROM user_register WHERE user_id='$user_id' ";
        $query_run = mysqli_query($connection, $query);

        if($query_run) {
            // Deletion successful
            $_SESSION['status'] = "Your Data is Deleted";
            $_SESSION['status_code'] = "success";
            header('Location: profile_manage.php'); 
        }
        else {
            // Deletion failed
            $_SESSION['status'] = "Your Data is NOT DELETED";       
            $_SESSION['status_code'] = "error";
            header('Location: profile_manage.php'); 
        }    
    }
?>

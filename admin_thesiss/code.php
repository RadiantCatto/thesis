     <?php
    // Import security.php file
    include_once 'security.php';

    // Handle registration form submission
    if(isset($_POST['registerbtn'])) {

        // Retrieve form data submitted through POST method
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['confirmpassword'];

        // Check if email already exists in database
        $email_query = "SELECT * FROM register WHERE email='$email' ";
        $email_query_run = mysqli_query($connection, $email_query);
        if(mysqli_num_rows($email_query_run) > 0) {
            // Email already taken
            $_SESSION['status'] = "Email Already Taken. Please Try Another one.";
            $_SESSION['status_code'] = "error";
            header('Location: register.php');  
        }
    else {
        // Email not taken
        if($password === $cpassword) {
            // Passwords match, insert data into database
            $query = "INSERT INTO register (username,email,password) VALUES ('$username','$email','$password')";
            $query_run = mysqli_query($connection, $query);

            if($query_run) {
                // Registration successful
                $_SESSION['status'] = "Admin Profile Added";
                $_SESSION['status_code'] = "success";
                header('Location: register.php');
            }
            else {
                // Registration failed
                $_SESSION['status'] = "Admin Profile Not Added";
                $_SESSION['status_code'] = "error";
                header('Location: register.php');  
            }
        }
        else {
            // Passwords do not match
            $_SESSION['status'] = "Password and Confirm Password Does Not Match";
            $_SESSION['status_code'] = "warning";
            header('Location: register.php');  
        }
    }
}

// Handle update form submission
    if(isset($_POST['updatebtn'])) {
    // Retrieve form data submitted through POST method
        $id = $_POST['edit_id'];
        $username = $_POST['edit_username'];
        $email = $_POST['edit_email'];
        $password = $_POST['edit_password'];

        // Update data in database
        $query = "UPDATE register SET username='$username', email='$email', password='$password' WHERE id='$id' ";
        $query_run = mysqli_query($connection, $query);

        if($query_run) {
            // Update successful
            $_SESSION['status'] = "Your Data is Updated";
            $_SESSION['status_code'] = "success";
            header('Location: register.php'); 
        }
        else {
            // Update failed
            $_SESSION['status'] = "Your Data is NOT Updated";
            $_SESSION['status_code'] = "error";
            header('Location: register.php'); 
    }
}

    // Handle delete form submission
    if(isset($_POST['delete_btn'])) {
    // Retrieve form data submitted through POST method
        $id = $_POST['delete_id'];

        // Delete data from database
        $query = "DELETE FROM register WHERE id='$id' ";
        $query_run = mysqli_query($connection, $query);

        if($query_run) {
            // Deletion successful
            $_SESSION['status'] = "Your Data is Deleted";
            $_SESSION['status_code'] = "success";
            header('Location: register.php'); 
        }
        else {
            // Deletion failed
            $_SESSION['status'] = "Your Data is NOT DELETED";       
            $_SESSION['status_code'] = "error";
            header('Location: register.php'); 
        }    
    }
        // This condition checks if the login button has been pressed
     if(isset($_POST['login_btn']))
        {
        // Get email and password from the form data
        $email_login = $_POST['emaill'];
        $password_login = $_POST['passwordd']; 

        // Create a query to search for a user with the given email and password
        $query = "SELECT * FROM register WHERE email='$email_login' AND password='$password_login' LIMIT 1";
        $query_run = mysqli_query($connection, $query);

        // If a row with the given email and password is found
        if(mysqli_fetch_array($query_run))
        {
            // Set the username in the session and redirect to the index page
            $_SESSION['username'] = $email_login;
            echo $_SESSION['username'];
            header('Location: index.php');
        } 
        else
        {
            // If the email and password do not match, set an error message in the session and redirect to the login page
            $_SESSION['status'] = "Email / Password is Invalid";
            header('Location: login.php');
        }

    }


    ?>
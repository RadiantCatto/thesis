<?php
$servername = "localhost";
$username = "id20529383_iot12";
$password = "Water_turbidity_syst3m";
$dbname = "id20529383_iot";


$api_key_value = "waytawo";

$api_key= $NTU = $VOLTS =  $REMARKS = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $NTU = test_input($_POST["NTU"]);
        $VOLTS = test_input($_POST["VOLTS"]);
        $REMARKS = test_input($_POST["REMARKS"]);
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO IoT (VOLTS, NTU, REMARKS)
        VALUES ('" . $VOLTS . "', '" . $NTU . "', '" . $REMARKS . "')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
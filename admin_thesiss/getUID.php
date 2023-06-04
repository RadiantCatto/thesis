<?php
    $cardID = $_POST["cardID"];
    $Write = "<?php $" . "cardID='" . $cardID . "'; " . "echo $" . "cardID;" . " ?>";
    file_put_contents('UIDContainer.php', $Write);

    // Retrieve user details based on the card ID and echo it as JSON
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM user_register WHERE cardID = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($cardID));
    $data = $q->fetch(PDO::FETCH_ASSOC);
    Database::disconnect();

    echo json_encode($data);
?>

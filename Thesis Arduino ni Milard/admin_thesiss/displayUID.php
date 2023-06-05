<?php
    $Write="<?php $" . "cardID=''; " . "echo $" . "cardID;" . " ?>";
    file_put_contents('UIDContainer.php',$Write);
?>
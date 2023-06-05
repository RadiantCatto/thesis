<?php

include_once 'database/dbconfig.php';

if($connection)
{
    // echo "Database Connected";
}
else
{
    header("Location: database/dbconfig.php");
}
?>
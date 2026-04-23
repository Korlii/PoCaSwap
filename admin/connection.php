<?php
// database connection
    try{
        $con = new mysqli("localhost", "root", "", "pocaswap");
    } catch(mysqli_sql_exception) {
        echo "Could not connect to database";
    }
?>

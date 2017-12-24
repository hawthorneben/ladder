<?php
    if(!empty($_POST["username"])) {
        include('../resources/db_connect.php');

        // Performing SQL query
        $query = "SELECT count(*) FROM player WHERE username='" . htmlspecialchars($_POST["username"]) . "'";
        $result = $connection->query($query);

        $row = $result->fetch(PDO::FETCH_ASSOC);
        $user_count = $row['count'];
        if($user_count>0) 
        {
            echo "0";
        }
        else 
        {
            echo "1";
        }
        
        $connection = null;
    }
?>
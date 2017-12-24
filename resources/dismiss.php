<?php
    if(!empty($_POST["id"])) {
        include('../resources/db_connect.php');

        // Performing SQL query
        $statement = $connection->prepare('update notification set dismissed = 1 where id = :code');

        $id = $_POST['id'];

        $statement->execute(array(":code"=>$id));

        if($statement->rowCount() == 1) 
        {
            echo "1";
        }
        else 
        {
            echo "0";
        }
        
        $connection = null;
    }
?>
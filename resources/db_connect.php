<?php
    // Connecting, selecting database
    $dbUsername = '';
    $dbPassword = '';
    try {
        $connection = new PDO("pgsql:dbname='ladder' host='localhost' password='$dbPassword' user='$dbUsername'",
                   $dbUsername, $dbPassword, array( PDO::ATTR_PERSISTENT => true));
     } 
     catch (PDOException $e) {
         print "Error!: " . $e->getMessage() . "<br/>";
         die();
     }
?>

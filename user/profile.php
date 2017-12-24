<!DOCTYPE html>
<html>
    <?php 
        include('../resources/header.php');
        include('../resources/redirect.php');
    ?>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 16px; margin-bottom: -65px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">
    <?php
        $username = $_SESSION['username'];
        $name = $_SESSION['name'];

        // Connecting, selecting database
        include('../resources/db_connect.php');

        // Performing SQL query
        $statement = $connection->prepare("select rank, name, email, phone, username from player where username = :user");
        $statement->execute(array(":user"=>$username));
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        echo "Hi there, ".$name."!</h5>";

        echo "<div class='w3-container w3-padding-64 w3-center'>
        <div class='theme-color floating-card padded-table w3-margin-top w3-round'>
            <div class='panel-body'>";
        
        // Printing results in HTML
        echo "<table class='spaced-table'>\n";
        echo "<tr><td>Rank</td><td>Name</td><td>Email</td><td>Phone Number</td>"
        ."<td>Username</td></tr>\n";
        echo "<tr>";
        echo "<td>".$user['rank']."</td>";
        echo "<td>".$user['name']."</td>";
        echo "<td>".$user['email']."</td>";
        echo "<td>".$user['phone']."</td>";
        echo "<td>".$user['username']."</td>";
        echo "</tr>";
        echo "</table>\n";
        
        echo "<br></div></div></div>";

        // Free connection
        $connection = null;
    ?>
    </header>
  </body>
</html>

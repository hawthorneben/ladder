<html>
<?php
    include('../resources/header.php');

    include('../resources/db_connect.php');

    $statement = $connection->prepare("SELECT id FROM password_reset WHERE username= :username");

    $statement->execute(array(":username"=>htmlspecialchars($_POST['username'])));

    $id = $statement->fetch(PDO::FETCH_ASSOC)['id'];

    // Check if they are using the correct ID
    if ($id == $_POST['id'])
    {
        $statement = $connection->prepare("UPDATE player SET password = :hashed WHERE username = :username");

        $hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $username = $_POST['username'];

        $statement->execute(array(":hashed"=>$hashed, ":username"=>$username));

        if ($statement->rowCount() == 1)
        {
            echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round' style='width:50%'>
                <div class='panel-body'>";
            echo "<h5>Password updated!</h5>";
            echo "<button onclick=\"document.getElementById('id01').style.display = 'block'\" id='login' class='card card__one w3-button "
            ."w3-padding-large w3-white w3-large w3-margin-top w3-round'>Login</button>";
            echo "</div></div></div>";
        }
        else
        {
            echo "<h1 style='margin-top: 50;'>Reset unsuccessful</h1>";
        }

        // Delete id from database
        $connection->query("DELETE FROM password_reset WHERE username = '$username'");

        // Free db connection
        $connection = null;
    }
    else
    {
        echo "<h1 style='margin-top: 50px'>Invalid reset link</h1>";
    }
?>
</body>
</html>
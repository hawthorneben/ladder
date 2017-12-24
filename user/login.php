<?php
    // Obtain username and password from form
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    // Connecting, selecting database
    include('../resources/db_connect.php');

    // Performing SQL query
    $statement = $connection->prepare("SELECT password, name FROM player WHERE username = :username");
    $statement->execute(array(":username"=>$username));

    // Get the user from the statement
    $line = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if the password hashes match
    if (password_verify($password, $line['password']) && isset($username))
    {
        // They have authenticated, we now start a new session
        session_unset();
        session_destroy();
        session_start();
    
        // Obtain the user's name
        $name = $line['name'];
        
        // Assign session variables
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $name;

        $message = "";

        $statement = $connection->prepare('select * from challenge where challengee = :user and accepted is null');

        $statement->execute(array(":user"=>$username));

        if ($statement->rowCount() > 0)
        {
            $message = "?challenge=true";
        }

        // Send the user to the ladder after login
        echo "<script>window.location='../ladder$message'</script>;";
    }
    else
    {
        // Invalid password...
        unset($_SESSION['username']);

        echo "<html>";
        
        include('../resources/header.php'); 

        // Inform user of invalid user/password match
        echo "<header class='w3-container theme-color bgimage w3-center' style='padding:128px 16px; margin-bottom: -65px;'>
            <h5 id='header' class='accent-color w3-margin w3-jumbo'>Invalid username / password!</h5>
            <a href='../index'>
                <button id='viewLadder' class='card card__one theme-color w3-button w3-padding-large w3-large w3-margin-top w3-round'>Go Home</button>
            </a>";

    }
    // Free connection
    $connection = null;
?>
    </header>
  </body>
</html>

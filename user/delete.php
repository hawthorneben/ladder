<!DOCTYPE html>
<html>
<?php 
    include('../resources/redirect.php');

    $password = htmlspecialchars($_POST['password']);
    $username = $_SESSION['username'];

    // Connecting, selecting database
    include('../resources/db_connect.php');

    // Performing SQL query
    $statement = $connection->prepare("SELECT password FROM player WHERE username = :user;");

    $statement->execute(array(":user"=>$username));
    $line = $statement->fetch(PDO::FETCH_ASSOC);

    if (password_verify($password, $line['password']))
    {
        session_unset();
        session_destroy();

        $queries = array(
            0 => "DELETE FROM game WHERE winner = :user OR loser = :user",
            1 => "DELETE FROM challenge WHERE challenger = :user OR challengee = :user",
            2 => "DELETE FROM player WHERE username = :user",
            3 => "DELETE FROM notification WHERE username = :user",
        );

        $usernameArray = array(":user"=>$username);
        foreach ($queries as $query)
        {
            $statement = $connection->prepare($query);
            $statement->execute($usernameArray);
        }

        echo '<head>
            <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
                <h5 id="header" class="accent-color w3-margin w3-jumbo">We hate to see you go!</h5>
            </header>';

            
        include('../resources/header.php');

        // Update ranks of each player lower on the ladder
        $statement = $connection->prepare("update player set rank = (rank - 1) where
                                            rank > :playerRank");

        $statement->execute(array(":playerRank"=>$rank));

        echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round'>
                    <div class='panel-body'>
                        <h5>Thanks for competing!</h5><br>
                        <a href='../index'><button class='w3-button w3-round w3-white accent-color'>Go Home</button></a>
                    </div>
                </div>
            </div>";

            

        // Free connection
        $connection = null;
    }
    else
    {
        echo "<div class='w3-container w3-padding-64 w3-center'>
        <div class='theme-color floating-card w3-margin-top w3-round'>
                <div class='panel-body'>
                    <h5>Incorrect Password!</h5><br>
                    <a href='deregister'><button class='w3-button w3-round w3-white accent-color'>Try Again</button></a>
                </div>
            </div>
        </div>";
    }
?>
  </body>
</html>

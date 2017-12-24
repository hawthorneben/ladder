<!DOCTYPE html>
<html>
    <?php 
        include('../resources/header.php'); 
        include('../resources/redirect.php');
        
        include('../resources/db_connect.php');

        $valid = true;
        $num_games = $_POST['games'];

        if ($num_games < 3 || $num_games > 5)
        {
            $valid = false;
        }

        for ($i = 1; $i <= $num_games && $valid; $i++)
        {
            $winner = htmlspecialchars($_POST["winner$i"]);
            $loser = htmlspecialchars($_POST["loser$i"]);
            $winner_score = htmlspecialchars($_POST["winner_score$i"]);
            $loser_score = htmlspecialchars($_POST["loser_score$i"]);
            $date = htmlspecialchars($_POST['date']);
            $time = htmlspecialchars($_POST['time']);
            
            if ($winner != $_SESSION['username'] && $loser != $_SESSION['username'])
            {
                $valid = false;
            }

            if ($valid)
            {
                try 
                {
                    // Prepare query
                    $statement = $connection->prepare("insert into game values (:winner, :loser, :date_time, :game_no, 
                                                        :winner_score, :loser_score);");
            
                    // Execute the query
                    $statement->execute(array(":winner"=>$winner, ":loser"=>$loser, ":date_time"=>date("Y-m-d H:i:s", time()),
                                ":game_no"=>$i, ":winner_score"=>$winner_score, 
                                ":loser_score"=>$loser_score));
                } 
                catch (PDOException $e) 
                {
                    echo "Error processing report";
                    die();
                }
            }
        }

        if ($valid)
        {
            // Erase challenge
            $query = "DELETE FROM challenge WHERE (challenger='".$_SESSION['username']."' 
                    OR challengee='".$_SESSION['username']."') AND accepted IS NOT NULL";

            $result = $connection->query($query);

            // Get the winner username
            $winnerUsername = htmlspecialchars($_POST['winnerUsername']);
            $loserUsername = htmlspecialchars($_POST['loserUsername']);
            
            // Get rank of winner and loser
            $statement = $connection->prepare("select rank from player where username = :user");
            $statement->execute(array(":user"=>$winnerUsername));
            $winnerRank = $statement->fetch(PDO::FETCH_ASSOC)['rank'];

            $statement = $connection->prepare("select rank from player where username = :user");
            $statement->execute(array(":user"=>$loserUsername));
            $loserRank = $statement->fetch(PDO::FETCH_ASSOC)['rank'];

            if ($loserRank < $winnerRank)
            {
                // CHANGE RANKS OF APPROPRIATE USERS
                $statement = $connection->prepare("update player set rank = 1000 where username = :user");
                $statement->execute(array(":user"=>$winnerUsername));
                $statement = $connection->prepare("update player set rank = rank + 1 where rank >= :loser and rank < :winnerRank");
                $statement->execute(array(":loser"=>$loserRank, ":winnerRank"=>$winnerRank));
                $statement = $connection->prepare("update player set rank = :loserRank where username = :winnerUsername;");
                $statement->execute(array(":loserRank"=>$loserRank, ":winnerUsername"=>$winnerUsername));
                echo "Rows affected: ".$statement->rowCount();
            }
        }
        
        // Free resultset
        $connection = null;

        if (!$valid)
        {
            echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round'>
                <div class='panel-body'>";
            echo "<h5>Error reporting scores! Please try again.</h5><br>";
            echo "<a href='index'><button id='goHome' class='card card__one w3-button "
            ."w3-padding-large w3-white w3-large w3-margin-top w3-round'>Go Home</button></a>";
            echo "<a href='../report/report' style='margin-left:10px'><button id='reportScores' class='card card__one w3-button
                w3-padding-large w3-white w3-large w3-margin-top w3-round'>Try again</button></a>";
            echo "</div></div></div>";
        }
        else
        {
            
            echo "<header class='w3-container theme-color bgimage w3-center' style='padding:128px 0px 0px 0px;'>
            <h5 id='header' class='accent-color w3-margin w3-jumbo'>Thank you for your report!</h5>
            <a href='../ladder'>
                <button id='home' class='card card__one theme-color w3-button w3-padding-large w3-large w3-margin-top w3-round'>View Ladder</button>
            </a>
            </header></div></div></div>";
        }
    ?>
            </div>
        </div>
    </div>
  </body>
</html>

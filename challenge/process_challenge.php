<!DOCTYPE html>
<html>
    <?php 
        include('../resources/header.php');
        include('../resources/redirect.php');
    ?>

    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">Thank you for your action</h5>
    </header>

    <?php 
        // Obtain username and user action
        $username = $_SESSION['username'];
        $action = htmlspecialchars($_POST['decision']);
        
        // Connecting, selecting database
        include('../resources/db_connect.php');

        // If the user does not wish to issue a challenge they will accept or deny
        if ($action != 'issue')
        {
            // Get the challenger that they are accepting or denying
            $challenger = $_POST['selection'];

            // If they wish to deny, delete challenge from database
            if ($action == 'deny')
            {
                // Get the specific challenge they wish to deny
                $date = $_POST['date'];

                // Performing delete query
                $statement = $connection->prepare("DELETE FROM challenge WHERE challenger = :userChallenger 
                                                AND challengee = :userChallengee
                                                AND scheduled = :dateTime");

                $statement->execute(array(":userChallenger"=>$challenger, ":userChallengee"=>$username,
                                            ":dateTime"=>$date));
                $line = $statement->fetch(PDO::FETCH_ASSOC);

                echo "<div class='w3-container w3-padding-64 w3-center'>
                <div class='theme-color floating-card w3-margin-top w3-round'>
                        <div class='panel-body'>
                            <h5>Challenge denied</h5><br>
                            <a href='../index'><button class='w3-button accent-color w3-white w3-round'>Go Home</button></a>
                        </div>
                    </div>
                </div>";

                $name = $_SESSION['name'];

                $statement = $connection->prepare("insert into notification (username, category, comments, id)
                                values (:user, :type, :comment, :uniqueid)");
                $statement->execute(array(":user"=>$challenger, ":type"=>"Challenge",
                                    ":comment"=>"$name has denied your challenge.",
                                        ":uniqueid"=>uniqid()));
            }
            else
            {
                // Accept challenge

                // Obtain time
                $date_time = date("Y-m-d H:i:s", time());

                // Get the scheduled date of the challenge
                $date = $_POST['date'];

                // Set accepted to the current time
                $statement = $connection->prepare("update challenge set accepted = :dateTime where
                                                    challenger = :challengerUser and
                                                    challengee = :challengeeUser and
                                                    scheduled = :date");
                $statement->execute(array(":dateTime"=>$date_time, ":challengerUser"=>$challenger,
                                            ":challengeeUser"=>$username, ":date"=>$date));
                if ($statement->rowCount() == 1)
                {
                    // Delete any remaining challenges
                    $statement = $connection->prepare("delete from challenge where accepted is null
                                                        and (challengee = :user or challenger = :user)");
                    $statement->execute(array(":user"=>$username));

                    echo "<div class='w3-container w3-padding-64 w3-center'>
                    <div class='theme-color floating-card w3-margin-top w3-round'>
                            <div class='panel-body'>
                                <h5>Challenge accepted</h5><br>
                                <a href='../index'><button class='w3-button accent-color w3-white w3-round'>Go Home</button></a>
                            </div>
                        </div>
                    </div>";

                $name = $_SESSION['name'];

                // Give relevant users notifications
                $statement = $connection->prepare("insert into notification (username, category, comments, id)
                            values (:user, :type, :comment, :uniqueid)");
                $statement->execute(array(":user"=>$challenger, ":type"=>"Challenge",
                                    ":comment"=>"$name has accepted your challenge.",
                                        ":uniqueid"=>uniqid()));
                    
                $statement = $connection->prepare("insert into notification (username, category, comments, id)
                                    values (:user, :type, :comment, :uniqueid)");
                $statement->execute(array(":user"=>$username, ":type"=>"Report",
                                    ":comment"=>"Report scores soon.",
                                        ":uniqueid"=>uniqid()));
                                        
                $statement = $connection->prepare("insert into notification (username, category, comments, id)
                                    values (:user, :type, :comment, :uniqueid)");
                $statement->execute(array(":user"=>$challenger, ":type"=>"Report",
                                    ":comment"=>"Report scores soon.",
                                        ":uniqueid"=>uniqid()));
                }
                else
                {
                    // Print failure message
                    echo "<h1 style='margin-top:60px'>Error accepting challenge</h1>";
                }

            }
        }
        else
        {
            // ISSUE CHALLENGE
            $player = $_POST['player'];
            
            // Create the date-time that they wish to issue the challenge
            $date = $_POST['date'];
            $time = $_POST['time'].":00";

            // Concatenate date and time
            $scheduled = $date." ".$time;

            // Obtain the current date-time
            $currentDate = date("Y-m-d H:i:s", time());

            // Performing insert query
            $statement = $connection->prepare("insert into challenge (challenger, challengee, issued, scheduled)
                                            values (:user, :player, :dateTime, :scheduledAt)");
            
            $statement->execute(array(":user"=>$username, ":player"=>$player,
                                    ":dateTime"=>$currentDate, ":scheduledAt"=>$scheduled));

            // Check if query affected the correct number of rows
            if ($statement->rowCount() == 1)
            {
                echo "<div class='w3-container w3-padding-64 w3-center'>
                <div class='theme-color floating-card w3-margin-top w3-round'>
                        <div class='panel-body'>
                            <h5>Challenge issued</h5><br>
                            <a href='../index'><button class='w3-button accent-color w3-white w3-round'>Go Home</button></a>
                        </div>
                    </div>
                </div>";

                $name = $_SESSION['name'];
                
                $statement = $connection->prepare("insert into notification (username, category, comments, id)
                                values (:user, :type, :comment, :uniqueid)");
                $statement->execute(array(":user"=>$player, ":type"=>"Challenge",
                                    ":comment"=>"$name has issued you a challenge.",
                                        ":uniqueid"=>uniqid()));
            }
        } 
        
        // Free connection
        $connection = null;
    ?>
  </body>
</html>

<!DOCTYPE html>
<html>
    <?php 
        include('resources/header.php');
        include('resources/redirect.php');
        
        if (isset($_GET['challenge']))
        {
            echo '<!-- Alert modal -->
            <div id="id02" class="modal w3-card">
              <span onclick="document.getElementById(\'id02\').style.display=\'none\'" 
            class="close" title="Close Modal">&times;</span>
            
              <!-- Modal Content -->
              <div class="modal-content animate w3-card">
                <div class="container">
                  <h3>You have outstanding challenges!</h3>
                </div>
            
                <div class="container" style=\'border-top: 1px solid #0E0E0C; margin: 0px 10px 0px 10px\'>
                  <a href="challenge/challenge"><button class="w3-button w3-round theme-color">Challenges</button></a>
                  <button onclick="document.getElementById(\'id02\').style.display=\'none\'" 
                        class="w3-button w3-round w3-white accent-color" style="margin-left: 10px">Dismiss</button>
                </div>
              </div>
            </div>';
    
            echo "<script>document.getElementById('id02').style.display = 'block';</script>";
        }
    ?>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">Ladder</h5>
    </header>
    <div class='w3-container w3-padding-64 w3-center'>
        <div class='theme-color floating-card padded-table w3-margin-top w3-round'>
            <div class='panel-body'>
    <?php
        // Returns $connection object
        include('resources/db_connect.php');

        // Performing SQL query
        $query = "select username, rank, name, 
            100.0 * ( (select count(winner) from match_view where winner = username)::float / 
            case (select count(winner) from match_view where username = winner or username = loser) 
            when 0 then 1 else (select count(winner) from match_view where username = winner or username = loser) 
            end )
        as match_wins_percent,
            100.0 * ( (select count(winner) from game where winner = username)::float / 
            case (select count(winner) from game where username = winner or username = loser) 
            when 0 then 1 else (select count(winner) from game where username = winner or username = loser) 
            end )
        as game_wins_percent,
            coalesce((select avg(winner_score) from game where winner = username) - 
            (select avg(loser_score) from game where username = winner), 0)
        as winning_margin,
            coalesce( (select avg(winner_score) from game where loser = username) - 
            (select avg(loser_score) from game where loser = username), 0)
        as losing_margin
        from player order by rank;";
        $result = $connection->query($query);

        $username = $_SESSION['username'];
        
        // Performing SQL query to determine who they can challenge
        $statement = $connection->prepare("select p2.username as \"challengee\"
        from player p1 join player p2 on ( (p2.rank >= (p1.rank - 3)) and (p2.rank < p1.rank) )
        where (p1.username not in (select challengee from challenge where accepted is not null))
            and (p2.username not in (select challenger from challenge where accepted is not null))
            and (p2.username not in (select challengee from challenge where accepted is not null))
            and (p1.username = :user);");

        $statement->execute(array(":user"=>$username));

        $challengees = array();

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $line)
        {
            array_push($challengees, $line['challengee']);
        }

        // Printing results in HTML
        echo "<table class='spaced-table'>\n";
        echo "<tr><td>Rank</td><td>Name</td><td>Match Wins Percentage</td><td>Game Wins Percentage</td>
                <td>Winning Margin</td><td>Losing Margin</td></tr>\n";

        $name = $_SESSION['name'];

        foreach ($result as $line) {
            $lineName = $line['name'];
            echo "<tr>";
            echo "<td>".$line['rank']."</td>";

            $playerUsername = $line['username'];

            $player = ($lineName == $name ? "<b>$name</b>" : $lineName);
            if (in_array($playerUsername, $challengees))
            {
                $player .= "  <button style='padding:5px' class='w3-white w3-round w3-button w3-small'
                                onclick=\"window.location='challenge/challenge?challenge=$playerUsername#issue'\">Challenge</button>";
            }
            echo "<td>".$player."</td>";
            echo "<td>".round($line['match_wins_percent'], 2)."</td>";
            echo "<td>".round($line['game_wins_percent'], 2)."</td>";
            echo "<td>".round($line['winning_margin'], 2)."</td>";
            echo "<td>".round($line['losing_margin'], 2)."</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Free resultset
        $result->closeCursor();

        // Close db connection
        $connection = null;
    ?>
            </div>
        </div>
    </div>
  </body>
</html>

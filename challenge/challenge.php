<!DOCTYPE html>
<html>
    <?php 
        include('../resources/header.php');
        include('../resources/redirect.php');
    ?>
    <head>
        <link rel="stylesheet" href="https://common.olemiss.edu/_js/pickadate.js-3.5.3/lib/themes/classic.css" id="theme_base">
        <link rel="stylesheet" href="https://common.olemiss.edu/_js/pickadate.js-3.5.3/lib/themes/classic.date.css" id="theme_date">
        <link rel="stylesheet" href="http://amsul.ca/pickadate.js/vendor/pickadate/lib/themes/classic.time.css" id="theme_time">
        <script src="https://common.olemiss.edu/_js/pickadate.js"></script>  
        <style>
            .picker__year {
                display: none !important;
            }
        </style>
    </head>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">Challenge Home</h5>
    </header>
    
    <?php
        // Obtain the username from the current session
        $username = $_SESSION['username'];

        // Connecting, selecting database
        include('../resources/db_connect.php');

        // Check if user is not in any accepted challenges
        $statement = $connection->prepare("select * from challenge where (challengee = :user 
                                    or challenger = :user) and accepted is not null");

        $statement->execute(array(":user"=>$username));

        // If they are not in any accepted challenges than they can accept and issue challenges
        if ($statement->rowCount() == 0)
        {
            
            $statement = $connection->prepare("SELECT challenger, issued, scheduled FROM challenge WHERE challengee = :user
                AND accepted IS NULL;");
    
            $statement->execute(array(":user"=>$username));
            
    
            if ($statement->rowCount())
            {
                echo "<div class='w3-container w3-padding-64 w3-center'>
                <div class='theme-color floating-card w3-margin-top w3-round'>
                    <div class='panel-body'>";
                echo "<h5>Outstanding challenges</h5>\n";
                // Printing results in HTML
                echo "<table class='spaced-table'>\n";
                echo "<tr><td>Challenger</td><td>Issued</td><td>Scheduled</td><td colspan=2>Action</td></tr>\n";
                foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $line) {
                    echo "\t<tr>\n";
                    // Get the name of the challenger
                    $challenger = $line['challenger'];
                    $result = $connection->query("select name from player where username = '$challenger'");
                    $name = $result->fetch(PDO::FETCH_ASSOC)['name'];

                    echo "<td>$name</td>";
                    echo "<td>".$line['issued']."</td>";
                    echo "<td>".$line['scheduled']."</td>";
                    echo "<form method='POST' action='process_challenge'>\n";
                    echo "<input type='hidden' name='decision' value='accept'/>\n";
                    echo "<input type='hidden' name='selection' value='".$line['challenger']."'/>\n";
                    echo "<input type='hidden' name='date' value='".$line['scheduled']."' />\n";
                    echo "<td><input type='submit' value='Accept' class='w3-card w3-button w3-white accent-color w3-round' /></td>\n";
                    echo "</form>\n";
        
                    echo "<form method='POST' action='process_challenge'>\n";
                    echo "<input type='hidden' name='decision' value='deny'/>\n";
                    echo "<input type='hidden' name='selection' value='".$line['challenger']."'/>\n";
                    echo "<input type='hidden' name='date' value='".$line['scheduled']."' />\n";
                    echo "<td><input type='submit' value='Deny' class='w3-card w3-button w3-white accent-color w3-round' /></td>\n";
                    echo "</form>\n";
                    echo "\t</tr>\n";
                    echo "</table><br>\n";
                }
            }
            else
            {
                echo "<div class='w3-container w3-padding-64 w3-center'>
                <div style='padding-bottom:8px' class='theme-color floating-card w3-margin-top w3-round'>
                    <div class='panel-body'>
                <h5>You have no outstanding challenges</h5>";
            }
            echo "</div></div></div>";

            // Performing SQL query
            $statement = $connection->prepare("select p2.username as \"challengee\"
            from player p1 join player p2 on ( (p2.rank >= (p1.rank - 3)) and (p2.rank < p1.rank) )
            where (p1.username not in (select challengee from challenge where accepted is not null))
                and (p2.username not in (select challenger from challenge where accepted is not null))
                and (p2.username not in (select challengee from challenge where accepted is not null))
                and (p1.username = :user);");
    
            $statement->execute(array(":user"=>$username));
    
            echo "<div class='w3-container w3-center' style='margin-bottom: 50px' id='issue'>
            <div id='challenge_who' class='theme-color floating-card w3-margin-top w3-round'>
                <div class='panel-body'>";
    
            if ($statement->rowCount() > 0)
            {
                // Printing results in HTML
                echo "<form action='process_challenge' method='POST'>";
                echo "<input type=hidden value='issue' name='decision'/>";
                echo "<h5>Issue challenge</h5>";
                echo "<table class='spaced-table'>\n";
                echo "<tr><td>Selection</td><td>Challengee</td></tr>\n";
        
                foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $line) {
                    echo "\t<tr>\n";
                    $challengee = $line['challengee'];
                    $result = $connection->query("select name from player where username = '$challengee'");
                    $name = $result->fetch(PDO::FETCH_ASSOC)['name'];
                    
                    $checked = "";
                    if (isset($_GET['challenge']) && $_GET['challenge'] == $challengee)
                    {
                        $checked = "checked='checked'";
                    }

                    echo "<td><input type='radio' name='player' value='$challengee' $checked required /></td>\n";
                    echo "<td>$name</td>\n";
                    echo "\t</tr>\n";
                }
                echo "<tr><td><p><label><i class='fa fa-calendar'></i> Date</label></p></td>";
                echo "<td><input id='date-picker' type='text' class='form-control w3-border w3-input' name='date' required></td></tr>";
                echo "<tr><td><p><label><i class='fa fa-clock-o'></i> Start Time</label></p></td>";
                echo "<td><input class='w3-input w3-border' id='startTime' type='text' name='time' required></td></tr>";
                echo "</table><br>\n";
                echo "<input type='submit' value='Challenge' class='w3-card w3-button w3-round w3-white accent-color' />";
                echo "</form></div></div></div>";
            }
            else
            {
                echo "<h5>There is no one available for you to challenge</h5></div></div></div>";
            }
        }
        else
        {
            echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round'>
                <div class='panel-body'>";
            echo "<h5>You are in an accepted challenge</h5>\n";
            echo "<a href='../report/report'><button class='w3-card w3-button w3-round w3-white accent-color'>Report scores</button></a>";
            echo "</div></div></div>";

            echo "<style>#challenge_who {padding-bottom:8px}</style>";
        }

        // Free connection
        $connection = null;
    ?>
    <script>
        $(function() {
            $('#startTime').pickatime({
                format: 'HH:i',
                hiddenName: true,
                interval: 30
            });
            $('#date-picker').pickadate({
					format: 'yyyy-mm-dd',
					min: new Date('2017/12/01'),
					max: new Date('2018/12/31')
				});
        });
    </script>
  </body>
</html>

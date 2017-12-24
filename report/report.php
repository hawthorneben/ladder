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
    <style>
        input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
        background-color: #ffdddd;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none; 
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
        }

        /* Mark the active step: */
        .step.active {
        opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
        background-color: #4CAF50;
        }
    </style>
    <script>

        var currentTab, player1Wins, player2Wins, player1, player2, games;

        $(function() {
            currentTab = 0; // Current tab is set to be the first tab (0)
            player1Wins = 0;
            player2Wins = 0;
            games = 0;
            player1 = $("#one").val();
            player2 = $("#two").val();
            showTab(currentTab); // Display the current tab
            
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


        function validate()
        {
            return false;
        }

        function validateScores(game)
        {
            var validIndicator = $("#valid" + (game + 1));
            var winnerName = $("#winner" + (game + 1)).val();
            var winnerScore = $("#winnerScore" + (game + 1));
            var loserScore = $("#loserScore" + (game + 1));

            var winner = parseInt(winnerScore.val());
            var loser = parseInt(loserScore.val());
            var valid = true;
            var message = "Invalid scores: ";

            if (loser >= winner)
            {
                valid = false;
                message += "loser score greater";
            }
            else if (winner < 15)
            {
                valid = false;
                message += "winner did not reach 15";
            }
            else if (winner == 15 && loser > 13)
            {
                valid = false;
                message += "did not win by at least 2";
            }
            else if (winner > 15 && loser != (winner - 2))
            {
                valid = false;
                message += "did not win by 2";
            }
            else // VALID
            {
                winnerName == player1 ? player1Wins++ : player2Wins++;
            }

            validIndicator.html(valid ? "Valid" : message);

            return valid;
        }

        function selectLoser(game)
        {
            var winner = $("#winner" + game);
            var loser = $("#loser" + game);

            if ($("#one").val() == winner.val())
            {
                loser.val($("#two").val());
            }
            else
            {
                loser.val($("#one").val());
            }
        }

    function showTab(n) {
        // This function will display the specified tab of the form ...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        // ... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        // ... and run a function that displays the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";

        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        
        if (n == -1)
        {
            // Subtract the win from the winner
            var winnerName = $("#winner" + (currentTab + 1)).val();

            winnerName == player1 ? player1Wins-- : player2Wins--;
        }

        // if you have reached the end of the form... :
        if (currentTab >= x.length || (player1Wins >= 3) || (player2Wins >= 3)) {
            //...the form gets submitted:
            games = player1Wins + player2Wins;
            $('#numGames').val(games);
            $('#winnerUsername').val(player1Wins > player2Wins ? player1 : player2);
            $('#loserUsername').val(player1Wins < player2Wins ? player1 : player2);
            //document.getElementById("regForm").submit();
            confirmInfo();
            return false;
        }

        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

        function validateForm() {
        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        for (i = 0; i < y.length; i++) {
            // If a field is empty...
            if (y[i].value == "") {
            // add an "invalid" class to the field:
            y[i].className += " invalid";
            // and set the current valid status to false:
            valid = false;
            }
        }
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
        }

        function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
        }

    function confirmInfo()
    {
        document.getElementById('obtain').style.display = 'none';
        document.getElementById('confirm').style.display = 'block';

        var header = "<td>Game</td><td>Winner</td><td>Winner Score</td><td>Loser Score</td>";

        $("#table").append("<table class='spaced-table' id='confirm_table'><tr>"+header+"</tr></table>");

        for (var i = 1; i <= games; i++)
        {
            var winnerUsername = $("#winner"+i).val();

            if ($("#one").val() == winnerUsername)
            {
                var winnerName = $("#one").html();
            }
            else
            {
                var winnerName = $("#two").html();
            }

            var winner = "<td>" + winnerName + "</td>";
            var winnerScore = "<td>" + $("#winnerScore"+i).val() + "</td>";
            var loserScore = "<td>" + $("#loserScore"+i).val() + "</td>";
            $("#confirm_table").append("<tr><td>"+i+"</td>"+winner+winnerScore+loserScore+"</tr>");
        }

        $("#table").append("<div id='buttons'></div>");

        $("#buttons").append("<button style='margin-top:15px'class='w3-button w3-round w3-white' onclick=\"document.getElementById('regForm').submit();\">Confirm</button>");
        $("#buttons").append("<button style='margin-top:15px'class='w3-button w3-round theme-color' onclick=\"startOver()\">Back</button>");
    }

    function startOver()
    {
        nextPrev(-1);
        document.getElementById('confirm').style.display = 'none';
        document.getElementById('obtain').style.display = 'block';

        $("#confirm_table").remove();
        $("#buttons").remove();
    }

    </script>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">Report Score</h5>
    </header>
    <?php
        $username = $_SESSION['username'];

        // Connecting, selecting database
        include('../resources/db_connect.php');

        // Performing SQL query
        $query = "SELECT * FROM challenge WHERE (challenger = '$username' OR challengee = '$username') AND accepted IS NOT NULL;";

        $result = $connection->query($query);

        if ($result->rowCount() > 0)
        {
            $line = $result->fetch(PDO::FETCH_ASSOC);

            $opponent = $line['challenger'];
            $scheduled = $line['scheduled'];
            if ($opponent == $username)
            {
                $opponent = $line['challengee'];
            }

            $myName = $_SESSION['name'];

            $result = $connection->query("select name from player where username = '$opponent'");
            $challenge = $result->fetch(PDO::FETCH_ASSOC);
            $opponentName = $challenge['name'];

            $datetime = new DateTime($scheduled);
            $date = $datetime->format('y-m-d');
            $time = $datetime->format('h:i');

            echo "<form action='submit_report'  id='regForm' method='POST' onsubmit='return validate()'>";
            echo "<div class='w3-container w3-center'>
                    <div class='theme-color floating-card w3-margin-top w3-round'>
                        <div class='panel-body'>
                            <h5>Played on</h5>
                            <p><label><i class='fa fa-calendar'></i> Date</label></p>
                            <input value='$date' id='date-picker' type='text' class='form-control w3-border w3-input' name='date' required>
                            <p><label><i class='fa fa-clock-o'></i> Start Time</label></p>
                            <input value='$time' class='w3-input w3-border' id='startTime' type='text' name='time' required>
                        </div>
                    </div>
                </div>";

            echo "<div style='padding-top:32px !important' id='obtain' class='w3-container w3-padding-64 w3-center'>
                    <div class='theme-color floating-card w3-margin-top w3-round'>
                        <div class='panel-body'>";
            echo "<input type=hidden id='numGames' name='games'/>";
            echo "<input type=hidden id='winnerUsername' name='winnerUsername'/>";
            echo "<input type=hidden id='loserUsername' name='loserUsername'/>";
            for ($i = 1; $i <= 5; $i++)
            {
                $style = $i != 1 ? "style='display:none'" : "";
                echo "<div $style class='tab'><h3>Game $i:</h3>
                <table>
                <tr>
                <td><label><i class='fa fa-user-circle'></i>  Winner</label></td>
                <td><select id='winner$i' name='winner$i' class='w3-input w3-border' placeholder='Winner username' onChange='selectLoser($i);' required>
                    <option disabled selected value> -- select a winner -- </option>
                    <option id='one' value='$username'>$myName</option>
                    <option id='two' value='$opponent'>$opponentName</option>
                </select></td>
                </tr>
                <td><input id='loser$i' type=hidden class='w3-input w3-border' name='loser$i' ></td>
                <tr>
                <td><label><i class='fa fa-info-circle'></i>  Winner Score</label></td>
                <td><input id='winnerScore$i' class='w3-input w3-border' type='number' placeholder=' Winner Score' name='winner_score$i' required></td>
                </tr>
                <tr>
                <td><label><i class='fa fa-info-circle'></i>  Loser Score</label></td>
                <td><input id='loserScore$i' class='w3-input w3-border' type='number' placeholder=' Loser Score' name='loser_score$i' required></td>
                </tr>
                <tr><td colspan=2><p id='valid$i'></p></td>
                <!--<td><input id='submit' type='submit' value='Report' class='w3-button w3-round w3-white w3-margin'></td></tr>-->
                </table>
                </div>";
            }

            echo "<div style='overflow:auto;'>
                <div style='float:right;'>
                    <button class='w3-button w3-round w3-white w3-margin' type='button' id='prevBtn' onclick='nextPrev(-1)'>Previous</button>
                    <button class='w3-button w3-round w3-white w3-margin' type='button' id='nextBtn' onclick='if (validateScores(currentTab)) {nextPrev(1);}'>Next</button>
                    </div>
                </div>
              
                <div style='text-align:center'>
                    <span class='step'></span>
                    <span class='step'></span>
                    <span class='step'></span>
                    <span class='step'></span>
                    <span class='step'></span>
                </div>
                </form>";
            echo "</div></div></div>";

            echo "<div id='confirm' style='display:none' class='w3-container w3-padding-64 w3-center'>
                    <div class='theme-color floating-card w3-margin-top w3-round'>
                        <div id='table' class='panel-body'><h5>Does this look right?</h5></div></div></div>";
        }
        else
        {
            echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round'>
                <div class='panel-body'>";
            echo "<h5>You are not in any accepted challenges</h5>";
            echo "<a href='../index'><button id='reportScores' class='card card__one w3-button "
            ."w3-white w3-margin-top w3-round'>Go Home</button></a>";
            echo "</div></div></div>";
        }

        // Free connection
        $connection = null;
    ?>
            </div>
        </div>
    </div>
  </body>
</html>

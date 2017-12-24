<!DOCTYPE html>
<html>
<?php
    // Inherit session
    session_start();

    // remove all session variables
    session_unset(); 

    // destroy the session 
    session_destroy(); 

    // Set each cookie to already expired
    foreach ($_COOKIE as $name => $value) {
        setcookie($name, '', 1);
    }

    include('../resources/header.php');
?>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">Come back soon!</h5>
    </header>
    <div class='w3-container w3-padding-64 w3-center'>
        <div class='panel-body'>
            <a href='../index'>
                <button id='reportScores' class='card card__one w3-button w3-padding-large theme-color w3-large w3-margin-top w3-round'>Go Home</button>
            </a>
        </div>
    </div>
  </body>
</html>

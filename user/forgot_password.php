<?php
    session_start();
    include('../resources/header.php');
?>
<body>

<?php
    if (isset($_POST['username']))
    {
        // Send email with reset link

        $username = htmlspecialchars($_POST['username']);

        include('../resources/db_connect.php');

        $query = $connection->prepare("SELECT email FROM player WHERE username = :username");
        
        $query->execute(array(":username"=>$username));

        if ($query->rowCount() > 0)
        {
            $email = $query->fetch(PDO::FETCH_ASSOC)['email'];

            // Prepare email
            $id = uniqid();
            $subject = "Password Reset Link";
            $message = "http://bhawthorne.bitnamiapp.com/user/reset_password?username=$username&id=$id";

            // Insert unique id into database
            $result = $connection->query("INSERT INTO password_reset VALUES ('$username', '$id');");

            // Send email
            if (mail($email, $subject, $message))
            {
                // email was successful
                echo "<h1 style='margin-top: 50px'>Email sent with password reset link!</h1>";
            }
            else
            {
                echo "<a><h1 style='margin-top: 50px'>$message</h1></a>";
                // Email was unsuccessful
                echo "<h1 style='margin-top: 50px'>Reset unsuccessful</h1>";
            }
        }

        $connection = null;
    }
    else
    {
        echo '<!-- The Modal -->
        <div id="id02" class="modal w3-card">
          <span onclick="document.getElementById(\'id02\').style.display=\'none\'" 
        class="close" title="Close Modal">&times;</span>
        
          <!-- Modal Content -->
          <form class="modal-content animate w3-card" action="forgot_password" method="POST">
        
            <div class="container">
              <label><b>Please enter your username</b></label>
              <input type="text" placeholder="Enter Username" name="username" required>
        
              <button type="submit" class="w3-button w3-round theme-color wide">Send reset link</button>
            </div>
        
            <div class="container" style=\'border-top: 1px solid #0E0E0C; margin: 0px 10px 0px 10px\'>
              <button type="button" onclick="window.location=\'index\'" class="w3-button w3-round theme-color">Cancel</button>
            </div>
          </form>
        </div>';

        echo "<script>document.getElementById('id02').style.display = 'block';</script>";
    }

?>

</body>
</html>
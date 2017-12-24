<?php
    include('../resources/header.php');
?>
<body>
<?php
    if (!isset($_GET['username']) || !isset($_GET['id']))
    {
        echo "<script>window.location='../index';</script>";
        exit();
    }
    else
    {
        include('../resources/db_connect.php');

        $statement = $connection->prepare("SELECT id FROM password_reset WHERE username= :username");

        $statement->execute(array(":username"=>htmlspecialchars($_GET['username'])));

        $id = $statement->fetch(PDO::FETCH_ASSOC)['id'];

        // Check if they are using the correct ID
        if ($id == $_GET['id'])
        {
            echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round' style='width:50%'>
                <div class='panel-body'><h3>Enter new password</h3>";
            echo "<form action='new_details' method='POST' onsubmit='return checkPasswordMatch()'>
                    <p>
                        <label><i class='fa fa-key'></i> Password</label>
                    </p>
                    <input id='pass1' class='w3-input w3-border' type='password' placeholder='Enter unique password' name='password' required> 
                    <p>
                        <label><i class='fa fa-key'></i> Reenter Password</label>
                    </p>
                    <input id='pass2' class='w3-input w3-border' type='password' placeholder='Reenter password' name='passconfirm' required> 
                    <br>
                    <p class='registrationFormAlert w3-tag w3-round' id='checkPasswordMatch'></p>
                    <input type='hidden' name='username' value=".$_GET['username']." />
                    <input type='hidden' name='id' value='$id' />
                    <br>
                    <input type='submit' class='w3-button w3-round w3-white' style='margin-top: 15px;' />
                </form>";
            echo "</div></div></div>";
        }
        else
        {
            echo "<h1 style='margin-top: 50px'>Invalid reset link</h1>";
        }
    }
?>
<script>
    function checkPasswordMatch() {
        var password = $("#pass1").val();
        var confirmPassword = $("#pass2").val();
        
        if (password != confirmPassword)
        {
            $("#checkPasswordMatch").html("Passwords do not match!");
            $("#checkPasswordMatch").addClass("w3-red");
            $("#checkPasswordMatch").removeClass("w3-green");
        }
        else
        {
            $("#checkPasswordMatch").html("Passwords match.");
            $("#checkPasswordMatch").addClass("w3-green");
            $("#checkPasswordMatch").removeClass("w3-red");
        }
    }

    $(document).ready(function()
    {
        $("#pass2").keyup(checkPasswordMatch);
        
    });
</script>
</body>
</html>
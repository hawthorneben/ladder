<!DOCTYPE html>
<html>
    <?php 
        include('../resources/header.php');
        include('../resources/redirect.php');
    ?>
    <header class="w3-container theme-color bgimage w3-center" style="padding:128px 0px 0px 0px;">
        <h5 id="header" class="accent-color w3-margin w3-jumbo">Are you sure you want to cancel your account?</h5>
    </header>
    <?php 
        echo "<div class='w3-container w3-padding-64 w3-center'>
            <div class='theme-color floating-card w3-margin-top w3-round'>
                    <div class='panel-body'>
                        <h5>Enter your password to confirm account deletion</h5><br>
                        <form action='delete' method='POST'>
                            <input type='password' class='' placeholder='Password' name='password' required><br>
                            <input type='submit' value='Delete Account' class='w3-button accent-color w3-white w3-round' />
                        </form>
                    </div>
                </div>
            </div>";
    ?>
  </body>
</html>

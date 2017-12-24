<!DOCTYPE html>
<html>
  <?php 
      include('resources/header.php'); 

      if (isset($_GET['redirect']) || isset($_GET['timeout']))
      {
        if ($_GET['redirect'] == "true")
        {
          $message = "You cannot access site details without logging in";
        }
        else if ($_GET['timeout'] == "true")
        {
          $message = "You have been logged out due to inactivity";
        }
        echo '<!-- Alert modal -->
        <div id="id02" class="modal w3-card">
          <span onclick="document.getElementById(\'id02\').style.display=\'none\'" 
        class="close" title="Close Modal">&times;</span>
        
          <!-- Modal Content -->
          <div class="modal-content animate w3-card">
            <div class="container">
              <h3>'.$message.'</h3>
            </div>
        
            <div class="container" style=\'border-top: 1px solid #0E0E0C; margin: 0px 10px 0px 10px\'>
              <button onclick="document.getElementById(\'id02\').style.display=\'none\'; 
                    document.getElementById(\'id01\').style.display=\'block\'" class="w3-button w3-round theme-color">Login</button>
              <button onclick="document.getElementById(\'id02\').style.display=\'none\'" 
                    class="w3-button w3-round w3-white accent-color" style="margin-left: 10px">Dismiss</button>
            </div>
          </div>
        </div>';

        echo "<script>document.getElementById('id02').style.display = 'block';</script>";
      }
  ?>
    <header class="w3-container w3-center accent-color" style="padding:128px 16px">
      <h1 class="w3-margin w3-jumbo">Ladder</h1>
      <p class="w3-xlarge">Challenge, Compete, Conquer</p>
      <a href="user/register">
        <button id="getStarted" class="card card__one theme-color w3-button w3-padding-large w3-large w3-margin-top w3-round">Get Started</button>
      </a>
    </header>

    <!-- First Grid -->
    <div class="w3-row-padding w3-padding-64 w3-container theme-color">
      <div class="w3-content">
        <div class="w3-twothird">
          <h1>About</h1>
          <h5 class="w3-padding-32">
            Rise through the ranks and challenge those who would stand in your way. 
            Challenge your opponents to a friendly game of racquetball and go head
            to head in a best out of 5 match to determine who will fall a rank and who will rise. 
          <br>
          <br>
            Anybody can register and you can challenge anybody up to three ranks above you. 
            View active statistics and strategize for optimum chances at rising to the top!
          </h5>
        </div>
      </div>
    </div>

    <div class="w3-container w3-center w3-padding-64 accent-color">
        <h1 class="w3-margin w3-xlarge">Strategy and cunning</h1>
    </div>

    <!-- Footer -->
    <footer class="w3-container w3-padding-64 w3-center accent-color"> 
      <span class="theme-color floating-card w3-margin-top w3-round">
        <p>Powered by <a href="https://www.w3schools.com/w3css/default.asp" target="_blank">w3.css</a></p><br>
        <p>Contact us by <a href="mailto:bhawthorne16@georgefox.edu">email</a></p>
      </span>
    </footer>
  </body>
  <script>
  
    // Get the modal
    var modal = document.getElementById('id01');
    var alertModal = document.getElementById('id02');
    
    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
        if (event.target == alertModal) {
            alertModal.style.display = "none";
        }
    }
  </script>
</html>
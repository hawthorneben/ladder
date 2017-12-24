<?php
  session_start();
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 300)) {
    // last request was more than 5 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time 
    session_destroy();   // destroy session data in storage

    echo "<script>window.location='../index?timeout=true';</script>";
    exit();
  }
  $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Ben's Ladder</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="../css/w3.css">
<link rel="stylesheet" href="../css/font-awesome.css">
<link rel="stylesheet" href="../css/site.css">
<link rel="stylesheet" href="../css/css">
<link rel="icon" href="../content/favicon.png" type="image/x-icon">
<link rel="shortcut icon" href="../content/favicon.png" type="image/x-icon">
<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous">
</script>

<script>
  function dismiss(id)
  {
    jQuery.ajax({
    url: "../resources/dismiss.php",
    data:'id='+id,
    type: "POST",
    success:function(data){
      if (data == "1")
      {
        // GOOD
        document.getElementById(id).style.display = 'none';
        //$("#" + id).style('display:none');
      }
      else
      {
        // BAD
        console.log('failure');
      }
  },
    // Who knows...
    error:function (){}
  });
  }
</script>
</head>
<body class="bgimage w3-animate-opacity" data-gr-c-s-loaded="true">
<div id="head">
  <!-- Navbar -->
  <div class="w3-top">
    <div class="w3-bar theme-color w3-card-2 w3-left-align w3-large">
      <a href="../index" class="w3-bar-item w3-button w3-padding-large w3-hover-white fa fa-home header-icon"></a>
      <?php 
       if (!isset($_SESSION['username']))
       {
         echo "<a href='../user/register' class='w3-bar-item w3-button w3-padding-large w3-hover-white'>Register</a>";
         echo "<a href'#login class='w3-button w3-bar-item w3-padding-large w3-white login' onclick=\"document.getElementById('id01').style.display='block'\">Login</a>";
       }
       else
       {
          echo "<a href='../ladder' class='w3-bar-item w3-button w3-padding-large w3-hover-white'>Ladder</a>";
          echo "<a href='../report/report' class='w3-bar-item w3-button w3-padding-large w3-hover-white'>Report Scores</a>";
          echo "<a href='../challenge/challenge' class='w3-bar-item w3-button w3-padding-large w3-hover-white'>Challenge Player</a>";
          echo "<div class='dropdown'>
            <button class='dropbtn'>".$_SESSION['name']
            ." <i class='fa fa-caret-down'></i>
            </button>
            <div class='dropdown-content'>
            <a href='../user/profile'>View Profile</a>
            <a href='../user/logout'>Logout</a>
            <a href='../user/deregister'>Delete Account</a></div></div>"; 

          
          include('db_connect.php');

          // Include notification tray
          $result = $connection->query("select * from notification where username = '".$_SESSION['username']."' and dismissed is null");
          if ($result->rowCount())
          {
            $icon = 'flag';
          }
          else
          {
            $icon = 'flag-o';
          }
          
          echo "<div class='login'><a href='../script.xml' class='w3-round w3-button w3-bar-item w3-hover-white fa fa-rss' style='margin-top:9px'></a>";
          echo "<a href='http://ben-hawthorne.com' class='w3-round w3-button w3-bar-item w3-hover-white fa fa-info-circle' style='margin:9px'></a>
                </div><div class='dropdown'><button class='dropbtn'><i class='fa fa-$icon'></i></button>";


          echo "<div class='dropdown-content'>";
          if ($result->rowCount())
          {
            foreach ($result as $notification)
            {
              switch ($notification['category'])
              {
                case "Challenge":
                  $link = "../challenge/challenge";
                  break;
                case "Report":
                  $link = "../report/report";
                  break;
              }
              echo "<p class='w3-medium' id='".$notification['id']."'>";
              echo $notification['category'].": "; 
              echo $notification['comments']." ";
              
              echo "<button style='margin-right: 5px' onclick=\"window.location='$link'\" class='w3-medium w3-round w3-button theme-color'>View</button>";
              echo "<button onclick=\"dismiss('".$notification['id']."')\" class='w3-medium w3-round w3-button theme-color'>Dismiss</button>";
              echo "</p>";
            }
          }
          else
          {
            echo "<a href='#'>No new notifications!</a>";
          }

          echo "</div></div>";
       }
      ?>
    </div>
  </div>
</div>

<!-- The Modal -->
<div id="id01" class="modal w3-card">
  <span onclick="document.getElementById('id01').style.display='none'" 
class="close" title="Close Modal">&times;</span>

  <!-- Modal Content -->
  <form class='modal-content animate w3-card' action='https://bhawthorne.bitnamiapp.com/user/login' method='POST'>
    <div class="container">
      <label><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="username" required>

      <label><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <button type="submit" class="w3-button w3-round theme-color wide">Login</button>
    </div>

    <div class="container" style='border-top: 1px solid #0E0E0C; margin: 0px 10px 0px 10px'>
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-round theme-color">Cancel</button>
      <span class="psw">Forgot <a href="../user/forgot_password">password?</a></span>
    </div>
  </form>
</div>
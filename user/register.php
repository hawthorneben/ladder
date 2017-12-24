<!DOCTYPE html>
<head>
<style>
#header {
	border-radius: 4;
	height: 120px;
}

#checkUsername {
	margin-top: 10px;
}

#cardForm {
	padding-bottom: 16px;
}

.decide-outcome {
	width: 33%;
}
</style>
<?php include('../resources/header.php'); ?>
<header class="w3-container theme-color bgimage w3-center" style="padding:128px 16px; margin-bottom: -65px;">
<h5 id="header" class="accent-color w3-margin w3-jumbo">Ladder Signup</h5>
</header>
  <div class='w3-container'>
    <div class='w3-panel w3-card accent-color w3-white' id="cardForm">
      <div class='panel-body'>
		<form action="https://bhawthorne.bitnamiapp.com/user/submit_registration" method="post" id='registerForm'>
			<p>
				<label><i class="fa fa-user-circle"></i> Username</label>
			</p>
			<input id="username" class="w3-input w3-border" type="text" placeholder="Enter unique username" name="username" onChange="validUsername(this)" required> 
			<button type='button' id="checkUsername" class="w3-button theme-color w3-round">Check Username Availability</button>
			<span id='available' style='margin-left: 5px; margin-top:3px;'></span>
			<p>
				<label><i class="fa fa-key"></i> Password</label>
			</p>
			<input id="pass1" class="w3-input w3-border" type="password" placeholder="Enter unique password" name="password" required> 
			<p>
				<label><i class="fa fa-key"></i> Reenter Password</label>
			</p>
			<input id="pass2" class="w3-input w3-border" type="password" placeholder="Reenter password" name="passconfirm" required> 
			<p class="registrationFormAlert w3-tag w3-round" id="checkPasswordMatch"></p>
			<p>
				<label><i class="fa fa-user-o"></i> Name</label>
			</p>
			<input class="w3-input w3-border" type="text" placeholder="Name" name="name" required>         
			<p>
				<label><i class="fa fa-envelope-o"></i> Email</label>
			</p>
			<input class="w3-input w3-border" type="email" placeholder="Email" name="email" required>
			<p>
				<label><i class="fa fa-phone"></i> Phone Number</label>
			</p>
			<input id='phone' class="w3-input w3-border" type="text" placeholder="xxxxxxxxxx" name="phone" onchange="validPhoneNumber(this)" required>
	  </form>
	  		<p>
				<button class="w3-button theme-color w3-left-align w3-round decide-outcome" type="submit" onclick="validate()"><i class="fa fa-plus-circle w3-margin-right"></i> Register</button>
				<button id="cancel" class="w3-button w3-gray w3-left-align w3-round decide-outcome"><i class="fa fa-times-circle w3-margin-right"></i> Cancel</button formnovalidate>
				<span style='color:red' id='error_message'></span>
			</p>
	  </div>
    </div>
  </div>
  <script>
	function validPhoneNumber(inputtxt)  
	{  
		var phoneno = /^\d{10}$/;
		if((inputtxt.value.match(phoneno)))  
		{  
			return true;  
		}  
		else  
		{  
			alert("Invalid Phone Number");  
			return false;  
		}  
	} 

	function validUsername(inputtxt)
	{
		var username = /^[a-z0-9]+$/i;
		if((inputtxt.value.match(username)))  
		{  
			return true;  
		}  
		else  
		{  
			alert("Invalid username: must be alphanumeric");  
			return false;  
		} 
	}

	function checkAvailability() {
		var available = true;
		jQuery.ajax({
			url: "../resources/check_availability.php",
			data:'username='+$("#username").val(),
			type: "POST",
			success:function(data){
				if (data != "1")
				{
					$("#available").html('The username is already taken');
					available = false;
					InvalidInputHelper(document.getElementById("username"), {
					defaultText: "Please enter username!",
					emptyText: "Please enter username!",
					invalidText: function (input) {
						return 'The username "' + input.value + '" is already taken!';
							}
					});
					$("#username").addClass("w3-border-red");
					$("#username").removeClass("w3-border-green");
				}
				else
				{
					$("#available").html('The username is available');
					$("#username").addClass("w3-border-green");
					$("#username").removeClass("w3-border-red");
				}
		},
			error:function (){}
		});
		return available;
	}
	
	function checkPasswordMatch() {
		var password = $("#pass1").val();
		var confirmPassword = $("#pass2").val();
		var match = false;
		
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
			match = true;
		}

		return match;
	}

	function validate()
	{
		if (checkPasswordMatch() && checkAvailability() && 
				validUsername(document.getElementById('username')) 
				&& validPhoneNumber(document.getElementById('phone')))
		{
			document.getElementById('registerForm').submit();
		}
		else
		{
			$("#error_message").html("One or more errors exist! Please review.");
		}
	}

  $(document).ready(function() {
  
    /**
	  * @author ComFreek
	  * @license MIT (c) 2013-2015 ComFreek <http://stackoverflow.com/users/603003/comfreek>
	  * Please retain this author and license notice!
	  */
	(function (exports) {
	    function valOrFunction(val, ctx, args) {
	        if (typeof val == "function") {
	            return val.apply(ctx, args);
	        } else {
	            return val;
	        }
	    }
	
	    function InvalidInputHelper(input, options) {
	        input.setCustomValidity(valOrFunction(options.defaultText, window, [input]));
	
	        function changeOrInput() {
	            if (input.value == "") {
	                input.setCustomValidity(valOrFunction(options.emptyText, window, [input]));
	            } else {
	                input.setCustomValidity("");
	            }
	        }
	
	        function invalid() {
	            if (input.value == "") {
	                input.setCustomValidity(valOrFunction(options.emptyText, window, [input]));
	            } else {
	               input.setCustomValidity(valOrFunction(options.invalidText, window, [input]));
	            }
	        }
	
	        input.addEventListener("change", changeOrInput);
	        input.addEventListener("input", changeOrInput);
	        input.addEventListener("invalid", invalid);
	    }
	    exports.InvalidInputHelper = InvalidInputHelper;
	})(window);
	
  	$("#cancel").click(function() {
		window.location="../index";
	});
	
	$("#pass2").keyup(checkPasswordMatch);
	
	$("#checkUsername").click(function() {
		checkAvailability();
	});
  });
  </script>
</body>
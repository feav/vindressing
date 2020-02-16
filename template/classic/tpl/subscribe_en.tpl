<div class="container topMargin">
	{errormessage}
	<H1>Register on PAS Script in 2 minutes!</H1>
	<p>
	you just have to fill in all the fields of the form below, then go to
in your email to activate your account by clicking on the link sent.
	</p>
	<form method="POST" id="myForm" onsubmit="return checkform();">
		<input type="email" id="email" name="email" placeholder="Your email address" class="inputbox">
		<div id="erroremail"></div>
		<input type="text" id="pseudo" name="pseudo" placeholder="Your username" class="inputbox">
		<input type="password" id="password" name="password" placeholder="Your password" class="inputbox">
		<div id="errorconfirm"></div>
		<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" class="inputbox">
		<input type="checkbox" id="accept" name="accept" value="yes"> I accept the general terms of use<br>
		<div id="errorcheck"></div>
		<input type="checkbox" name="optin"> I wish to receive offers from the partners of the site<br>
		{recaptcha}
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Create my account" class="btnConfirm">
		</div>
	</form>
	<script>
	function checkform()
	{
		var error = 0;
		var email = $('#email').val();
		var pseudo = $('#pseudo').val();
		var password = $('#password').val();
		var confirm_password = $('#confirm_password').val();
		var accept = $('#accept').attr('checked');
		
		$('#email').css('border','1px solid #dddddd');
		$('#pseudo').css('border','1px solid #dddddd');
		$('#password').css('border','1px solid #dddddd');
		$('#confirm_password').css('border','1px solid #dddddd');
		$('#errorconfirm').html('');
		$('#erroremail').html('');
		$('#errorcheck').html('');
		
		if(email == '')
		{
			error = 1;
			$('#email').css('border','1px solid #ff0000');
		}
		
		if(pseudo == '')
		{
			error = 1;
			$('#pseudo').css('border','1px solid #ff0000');
		}
		
		if(password == '')
		{
			error = 1;
			$('#password').css('border','1px solid #ff0000');
		}
		
		if(confirm_password == '')
		{
			error = 1;
			$('#confirm_password').css('border','1px solid #ff0000');
		}
		
		if(accept == '')
		{
			error = 1;
			$('#errorcheck').html('<font color=red><b>You must accept the terms and conditions to create an account</b></font><br>');
		}
		
		if(password != confirm_password)
		{
			error = 1;
			$('#password').css('border','1px solid #ff0000');
			$('#confirm_password').css('border','1px solid #ff0000');
			$('#errorconfirm').html('<font color=red><b>The password does not match the confirmation</b></font>');
		}		
		
		if(error == 0)
		{
			$.post("{url_script}/api/checkemail.php?email="+encodeURI(email), function( data ) 
			{
				if(data == 'ok')
				{
					document.getElementById("myForm").submit();
				}
				else
				{
					$('#erroremail').html('<font color=red><b>This email is already used by another account</b></font>');
				}
			});
			return false;
		}
		else
		{
			return false;
		}
	}
	</script>
</div>
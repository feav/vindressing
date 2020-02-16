<div class="container topMargin">
	{errormessage}
	<H1>Регистрирайте се на PAS Script за 2 минути!</H1>
	<p>
	просто трябва да попълните всички полета на формата по-долу, след това отидете на
в имейла си, за да активирате профила си, като кликнете върху изпратената връзка.
	</p>
	<form method="POST" id="myForm" onsubmit="return checkform();">
		<input type="email" id="email" name="email" placeholder="Вашият имейл адрес" class="inputbox">
		<div id="erroremail"></div>
		<input type="text" id="pseudo" name="pseudo" placeholder="Потребителското ви име" class="inputbox">
		<input type="password" id="password" name="password" placeholder="Вашата парола" class="inputbox">
		<div id="errorconfirm"></div>
		<input type="password" id="confirm_password" name="confirm_password" placeholder="Потвърдете паролата си" class="inputbox">
		<input type="checkbox" id="accept" name="accept" value="yes"> Приемам условията за ползване<br>
		<div id="errorcheck"></div>
		<input type="checkbox" name="optin"> Искам да получавам оферти от партньорите на сайта<br>
		{recaptcha}
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Създайте моя профил" class="btnConfirm">
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
			$('#errorcheck').html('<font color=red><b>Трябва да приемете Общите условия за създаване на профил</b></font><br>');
		}
		
		if(password != confirm_password)
		{
			error = 1;
			$('#password').css('border','1px solid #ff0000');
			$('#confirm_password').css('border','1px solid #ff0000');
			$('#errorconfirm').html('<font color=red><b>Паролата не съответства на потвърждението</b></font>');
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
					$('#erroremail').html('<font color=red><b>Този имейл вече се използва от друг профил</b></font>');
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
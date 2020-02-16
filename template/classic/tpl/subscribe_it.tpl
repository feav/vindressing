<div class="container topMargin">
	{errormessage}
	<H1>Registrati su PAS Script in 2 minuti!</H1>
	<p>
	devi solo compilare tutti i campi del modulo sottostante, quindi andare a
nella tua email per attivare il tuo account cliccando sul link inviato.
	</p>
	<form method="POST" id="myForm" onsubmit="return checkform();">
		<input type="email" id="email" name="email" placeholder="Il tuo indirizzo email" class="inputbox">
		<div id="erroremail"></div>
		<input type="text" id="pseudo" name="pseudo" placeholder="Il tuo nome utente" class="inputbox">
		<input type="password" id="password" name="password" placeholder="La tua password" class="inputbox">
		<div id="errorconfirm"></div>
		<input type="password" id="confirm_password" name="confirm_password" placeholder="Conferma la tua password" class="inputbox">
		<input type="checkbox" id="accept" name="accept" value="yes"> Accetto i termini e le condizioni d'uso<br>
		<div id="errorcheck"></div>
		<input type="checkbox" name="optin"> Desidero ricevere offerte dai partner del sito<br>
		{recaptcha}
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Crea il mio account" class="btnConfirm">
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
			$('#errorcheck').html('<font color=red><b>Devi accettare i termini e le condizioni per creare un account</b></font><br>');
		}
		
		if(password != confirm_password)
		{
			error = 1;
			$('#password').css('border','1px solid #ff0000');
			$('#confirm_password').css('border','1px solid #ff0000');
			$('#errorconfirm').html('<font color=red><b>La password non corrisponde alla conferma</b></font>');
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
					$('#erroremail').html('<font color=red><b>Questa email è già utilizzata da un altro account</b></font>');
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
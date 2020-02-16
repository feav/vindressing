<div class="container topMargin">
	{errormessage}
	<H1>S'inscrire en 2 minutes chrono !</H1>
	<p>
	il vous suffit de remplir tous les champs du formulaire ci-dessous, puis Rendez vous
	dans votre messagerie pour activer votre compte en cliquant sur le lien envoyé.
	</p>
	<form method="POST" id="myForm" onsubmit="return checkform();">
		<select name="type_account" id="type_account" onchange="accountType();" class="inputbox">
			<option value="particulier">Particulier</option>
			<option value="professionel">Professionel</option>
		</select>
		<input type="email" id="email" name="email" placeholder="Votre adresse email" class="inputbox">
		<div id="erroremail"></div>
		<div id="particulier-name">
			<input type="text" id="pseudo" name="pseudo" placeholder="Votre pseudo" class="inputbox">
		</div>
		<input type="password" id="password" name="password" placeholder="Votre mot de passe" class="inputbox">
		<div id="errorconfirm"></div>
		<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer votre mot de passe" class="inputbox">
		<input type="checkbox" id="accept" name="accept" value="yes"> J'accepte les conditions générales d'utilisation<br>
		<div id="errorcheck"></div>
		<input type="checkbox" name="optin"> Je souhaite recevoir des offres des partenaires du site<br>
		{recaptcha}
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Créer mon compte" class="btnConfirm">
		</div>
	</form>
	<script>
	function accountType()
	{
		if($('#type_account').val() == 'particulier')
		{
			$('#particulier-name').html('<input type="text" id="pseudo" name="pseudo" placeholder="Votre pseudo" class="inputbox">');
		}
		else
		{
			$('#particulier-name').html('<input type="text" id="pseudo" name="pseudo" placeholder="Nom de votre entreprise" class="inputbox">');
		}
	}
	
	function checkform()
	{
		var error = 0;
		var email = $('#email').val();
		var pseudo = $('#pseudo').val();
		var password = $('#password').val();
		var confirm_password = $('#confirm_password').val();
		
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
		
		if($('input[name=accept]').is(':checked'))
		{
			
		}
		else
		{
			error = 1;
			$('#errorcheck').html('<font color=red><b>Vous devez accepter les conditions générale pour créer un compte</b></font><br>');
		}
		
		if(password != confirm_password)
		{
			error = 1;
			$('#password').css('border','1px solid #ff0000');
			$('#confirm_password').css('border','1px solid #ff0000');
			$('#errorconfirm').html('<font color=red><b>Le mot de passe ne correspond pas à la confirmation</b></font>');
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
					$('#erroremail').html('<font color=red><b>Cette email est déjà utiliser par un autre compte</b></font>');
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
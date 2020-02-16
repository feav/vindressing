<div class="container topMargin">
	<H1>Vous avez oubliée votre mot de passe ?</H1>
	<p>
	Entrer l'adresse email de votre compte pour réinitialiser votre mot de passe.
	</p>
	{errormail}
	<form method="POST" action="lostpassword.php">
		<input type="email" id="email" name="email" placeholder="Entrer votre adresse email" class="inputbox">
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Réinitialiser votre mot de passe" class="btnConfirm">
		</div>
	</form>
	<div class="boxsubscribe-login" style="float:right;">
		<p>
		&nbsp;
		</p>
		<a href="login.php" class="btnConfirm">Retour</a>
	</div>
</div>
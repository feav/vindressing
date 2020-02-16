<div class="container topMargin">
	<H1>Accedi al tuo account</H1>
	{error_message}
	<form method="POST" action="connexion.php">
		<input type="email" id="email" name="email" placeholder="Il tuo indirizzo email" class="inputbox">
		<input type="password" id="password" name="password" placeholder="La tua password" class="inputbox">
		<div class="login-btn-connexion">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Accesso" class="btnConfirm">
		</div>
	</form>	
	<div class="boxsubscribe-login">
		<p>Non hai un account che puoi creare gratuitamente</p>
		<div class="btn-separate-login">
			<button onclick="location.href='lostpassword.php'" class="btnConfirm">Password dimenticata?</button>
		</div>
		<div class="btn-separate-login">
			<button onclick="location.href='subscribe.php'" class="btnConfirm">Crea un account gratuito</button>
		</div>
	</div>
</div>
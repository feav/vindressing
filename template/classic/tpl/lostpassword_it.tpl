<div class="container topMargin">
	<H1>Hai dimenticato la password?</H1>
	<p>
	Inserisci l'indirizzo email del tuo account per reimpostare la tua password.
	</p>
	{errormail}
	<form method="POST" action="lostpassword.php">
		<input type="email" id="email" name="email" placeholder="Inserisci il tuo indirizzo email" class="inputbox">
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Reimposta la tua password" class="btnConfirm">
		</div>
	</form>
	<div class="boxsubscribe-login" style="float:right;">
		<p>
		&nbsp;
		</p>
		<a href="login.php" class="btnConfirm">Ritorno</a>
	</div>
</div>
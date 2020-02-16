<div class="container topMargin">
	<H1>Sign into your account</H1>
	{error_message}
	<form method="POST" action="connexion.php">
		<input type="email" id="email" name="email" placeholder="Your email address" class="inputbox">
		<input type="password" id="password" name="password" placeholder="Your password" class="inputbox">
		<div class="login-btn-connexion">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Login" class="btnConfirm">
		</div>
	</form>	
	<div class="boxsubscribe-login">
		<p>You do not have an account you can create for free</p>
		<div class="btn-separate-login">
			<button onclick="location.href='lostpassword.php'" class="btnConfirm">Forgot your password ?</button>
		</div>
		<div class="btn-separate-login">
			<button onclick="location.href='subscribe.php'" class="btnConfirm">Create a free account</button>
		</div>
	</div>
</div>
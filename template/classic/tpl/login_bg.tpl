<div class="container topMargin">
	<H1>Влезте в профила си</H1>
	{error_message}
	<form method="POST" action="connexion.php">
		<input type="email" id="email" name="email" placeholder="Вашият имейл адрес" class="inputbox">
		<input type="password" id="password" name="password" placeholder="Вашата парола" class="inputbox">
		<div class="login-btn-connexion">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="логин" class="btnConfirm">
		</div>
	</form>	
	<div class="boxsubscribe-login">
		<p>Нямате профил, който можете да създадете безплатно</p>
		<div class="btn-separate-login">
			<button onclick="location.href='lostpassword.php'" class="btnConfirm">Забравена парола?</button>
		</div>
		<div class="btn-separate-login">
			<button onclick="location.href='subscribe.php'" class="btnConfirm">Създайте безплатен акаунт</button>
		</div>
	</div>
</div>
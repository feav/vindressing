<div class="container topMargin">
	<H1>Забравена парола?</H1>
	<p>
	Въведете имейл адреса на профила си, за да зададете отново паролата си.
	</p>
	{errormail}
	<form method="POST" action="lostpassword.php">
		<input type="email" id="email" name="email" placeholder="Въведете имейл адреса си" class="inputbox">
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Нулирайте паролата си" class="btnConfirm">
		</div>
	</form>
	<div class="boxsubscribe-login" style="float:right;">
		<p>
		&nbsp;
		</p>
		<a href="login.php" class="btnConfirm">връщане</a>
	</div>
</div>
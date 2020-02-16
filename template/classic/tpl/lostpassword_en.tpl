<div class="container topMargin">
	<H1>Forgot your password?</H1>
	<p>
	Enter the email address of your account to reset your password.
	</p>
	{errormail}
	<form method="POST" action="lostpassword.php">
		<input type="email" id="email" name="email" placeholder="Enter your email address" class="inputbox">
		<div style="margin-top:10px;margin-bottom:10px;">
		<input type="hidden" name="action" value="1">
		<input type="submit" value="Reset your password" class="btnConfirm">
		</div>
	</form>
	<div class="boxsubscribe-login" style="float:right;">
		<p>
		&nbsp;
		</p>
		<a href="login.php" class="btnConfirm">Return</a>
	</div>
</div>
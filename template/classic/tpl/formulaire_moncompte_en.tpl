<form method="POST">
	<label>Email address :</label>
	{errormail}
	<input type="email" name="email" placeholder="Your email address" value="{email}" class="inputbox">
	<label>Username :</label>
	{errorpseudo}
	<input type="text" name="pseudo" placeholder="The nickname visible on your ad" value="{pseudo}" class="inputbox">
	<label>Password :</label>
	<input type="password" name="password" placeholder="Your password" class="inputbox">
	{errorpassword}
	<label>Confirm your password :</label>
	<input type="password" name="password2" placeholder="Confirm your password" class="inputbox">
	{errorconfirm}
	<input type="hidden" name="action" value="1">
	<input type="submit" value="Edit" class="blueBtn">
</form>
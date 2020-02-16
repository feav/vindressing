<form method="POST">
	<label>Adresse Email :</label>
	{errormail}
	<input type="email" name="email" placeholder="Votre adresse email" value="{email}" class="inputbox">
	<label>Pseudo :</label>
	{errorpseudo}
	<input type="text" name="pseudo" placeholder="Le pseudo visible sur vos annonce" value="{pseudo}" class="inputbox">
	<label>Mot de passe :</label>
	<input type="password" name="password" placeholder="Votre mot de passe" class="inputbox">
	{errorpassword}
	<label>Confirmer :</label>
	<input type="password" name="password2" placeholder="Confirmer votre mot de passe" class="inputbox">
	{errorconfirm}
	<input type="hidden" name="action" value="1">
	<input type="submit" value="Modifier" class="blueBtn">
</form>
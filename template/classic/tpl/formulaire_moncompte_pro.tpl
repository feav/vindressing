<form method="POST" enctype="multipart/form-data">
	<label>Adresse Email :</label>
	{errormail}
	<input type="email" name="email" placeholder="Votre adresse email" value="{email}" class="inputbox">
	<label>Nom de l'entreprise :</label>
	{errorpseudo}
	<input type="text" name="pseudo" placeholder="Le pseudo visible sur vos annonce" value="{pseudo}" class="inputbox">
	<label>Logo de l'entreprise :</label>
	{logo}
	<input type="file" name="logo" class="inputbox">
	<label>Catégorie principal :</label>
	<select name="categorie" class="inputbox">
	{categorie}
	</select>
	<label>Slogan :</label>
	<input type="text" name="slogan" placeholder="Slogan de votre entreprise" value="{slogan}" class="inputbox">
	<label>Description :</label>
	<textarea name="description" class="textbox" placeholder="Description de votre entreprise">{description}</textarea>
	<label>Adresse :</label>
	<input type="text" name="adresse" placeholder="Adresse de l'entreprise" value="{adresse}" class="inputbox">
	<label>Site internet :</label>
	<input type="text" name="site_internet" placeholder="Site internet" value="{site_internet}" class="inputbox">
	{errorwebsite}
	<label>Mot de passe :</label>
	<input type="password" name="password" placeholder="Votre mot de passe" class="inputbox">
	{errorpassword}
	<label>Confirmer :</label>
	<input type="password" name="password2" placeholder="Confirmer votre mot de passe" class="inputbox">
	{errorconfirm}
	<input type="hidden" name="action" value="2">
	<input type="hidden" name="md5" value="{md5}">
	<input type="submit" value="Modifier" class="blueBtn">
</form>
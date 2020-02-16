<form method="POST">
	<label>Indirizzo email :</label>
	{errormail}
	<input type="email" name="email" placeholder="Il tuo indirizzo email" value="{email}" class="inputbox">
	<label>Nickname :</label>
	{errorpseudo}
	<input type="text" name="pseudo" placeholder="Il nickname visibile sul tuo annuncio" value="{pseudo}" class="inputbox">
	<label>Password :</label>
	<input type="password" name="password" placeholder="La tua password" class="inputbox">
	{errorpassword}
	<label>Conferma :</label>
	<input type="password" name="password2" placeholder="Conferma la tua password" class="inputbox">
	{errorconfirm}
	<input type="hidden" name="action" value="1">
	<input type="submit" value="Cambiamento" class="blueBtn">
</form>
<form method="POST">
	<label>Имейл адрес:</label>
	{errormail}
	<input type="email" name="email" placeholder="Вашият имейл адрес" value="{email}" class="inputbox">
	<label>Потребител :</label>
	{errorpseudo}
	<input type="text" name="pseudo" placeholder="Вижда се псевдоним в рекламата Ви" value="{pseudo}" class="inputbox">
	<label>парола:</label>
	<input type="password" name="password" placeholder="Вашата парола" class="inputbox">
	{errorpassword}
	<label>потвърдете:</label>
	<input type="password" name="password2" placeholder="Потвърдете паролата си" class="inputbox">
	{errorconfirm}
	<input type="hidden" name="action" value="1">
	<input type="submit" value="промяна" class="blueBtn">
</form>
<div class="container topMargin" style="margin-bottom: 90px;">
	<div class="mega-block">
		<div class="big-block-gauche">
		{msg_valid}
		<H1>Изпращане на съобщение до "{username}"</H1>
		<p>
		Не забравяйте да включите телефонния си номер, за да може рекламодателят лесно да се свърже с вас. Всяка реклама или спам ще бъдат премахнати.
		</p>
		<form method="POST">
			<label><b>Име *:</b></label>
			<input type="text" name="nom" class="inputbox">
			<label><b>Имейл *:</b></label>
			<input type="email" name="email" class="inputbox">
			<label><b>Съобщение *:</b></label>
			<textarea name="message" class="textareabox"></textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="изпращам" class="blueBtn">
		</form>
		</div>
	</div>
</div>
<div class="container topMargin" style="margin-bottom: 90px;">
	{errormessage}
	<H1>Подайте сигнал за рекламата</H1>
	Препоръчваме ви да изберете причината за заявката си в менюто по-долу. Ще намерите текст с информация, който отговаря на често срещаните случаи, срещани от нашите потребители и клиенти.
	<br><br>
	Заявката ви за отчитане на рекламата ще бъде прегледана възможно най-скоро от нашите екипи, възможно е прегледът на молбата ви да отнеме до 6 дни.
	<div class="signaler-box">
		<form method="POST">
			<label><b>Причина *:</b></label><br><br>
			{errormotif}
			<input type="radio" name="motif" value="fraude"> измама
			<input type="radio" name="motif" value="doublon"> дубликат
			<input type="radio" name="motif" value="mauvaise_categorie"> Лоша категория
			<input type="radio" name="motif" value="deja_vendu"> Вече е продаден
			<input type="radio" name="motif" value="discrimination"> дискриминация
			<input type="radio" name="motif" value="chien_chat"> Куче и котка
			<input type="radio" name="motif" value="contrefacon"> фалшификация
			<input type="radio" name="motif" value="autre_abus">Други злоупотреби<br><br>
			<label><b>Име *</b></label>
			{erreurnom}
			<input type="text" name="nom" value="{nom}" class="inputbox">
			<label><b>Имейл *</b></label>
			{erreurmail}
			<input type="email" name="email" value="{email}" class="inputbox">
			<label><b>Вашето съобщение *</b></label>
			{erreurmessage}
			<textarea class="textareabox" name="message">{message}</textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="изпращам" class="blueBtn">
		</form>
	</div>
</div>
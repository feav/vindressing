<div class="container topMargin" style="margin-bottom: 90px;">
	<div class="mega-block">
		<div class="big-block-gauche">
		{msg_valid}
		<H1>Invia un messaggio a "{username}"</H1>
		<p>
		Ricordati di includere il tuo numero di telefono in modo che l'inserzionista possa contattarti facilmente. Qualsiasi pubblicità o spamming sarà eliminata.
		</p>
		<form method="POST">
			<label><b>Nome * :</b></label>
			<input type="text" name="nom" class="inputbox">
			<label><b>Email * :</b></label>
			<input type="email" name="email" class="inputbox">
			<label><b>Messaggio * :</b></label>
			<textarea name="message" class="textareabox"></textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Inviare" class="blueBtn">
		</form>
		</div>
	</div>
</div>
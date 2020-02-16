<div class="container topMargin" style="margin-bottom: 90px;">
	<div class="mega-block">
		<div class="big-block-gauche">
		{msg_valid}
		<H1>Envoyez un message à "{username}"</H1>
		<p>
		Pensez à indiquer vos coordonnées téléphoniques pour que l'annonceur puisse vous contacter facilement. Tout démarchage publicitaire ou spamming sera éliminé.
		</p>
		<form method="POST">
			<label><b>Nom * :</b></label>
			<input type="text" name="nom" class="inputbox">
			<label><b>Email * :</b></label>
			<input type="email" name="email" class="inputbox">
			<label><b>Message * :</b></label>
			<textarea name="message" class="textareabox"></textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Envoyez" class="blueBtn">
		</form>
		</div>
	</div>
</div>
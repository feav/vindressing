<div class="container topMargin" style="margin-bottom: 90px;">
	{errormessage}
	<H1>Segnala annuncio</H1>
	Ti suggeriamo di selezionare il motivo della tua richiesta nel menu sottostante. Troverai un testo di informazioni che risponde ai casi frequenti incontrati dai nostri utenti e clienti.
	<br><br>
	La tua richiesta di rapporti sull'annuncio verrà esaminata al più presto dai nostri team, è possibile che la revisione della tua richiesta richieda fino a 6 giorni.
	<div class="signaler-box">
		<form method="POST">
			<label><b>Ragione * :</b></label><br><br>
			{errormotif}
			<input type="radio" name="motif" value="fraude"> Frode
			<input type="radio" name="motif" value="doublon"> Duplicare
			<input type="radio" name="motif" value="mauvaise_categorie"> Cattiva categoria
			<input type="radio" name="motif" value="deja_vendu"> Già venduto
			<input type="radio" name="motif" value="discrimination"> Discriminazione
			<input type="radio" name="motif" value="chien_chat"> Cane e gatto
			<input type="radio" name="motif" value="contrefacon"> Contraffazione
			<input type="radio" name="motif" value="autre_abus"> Altro abuso<br><br>
			<label><b>Nome *</b></label>
			{erreurnom}
			<input type="text" name="nom" value="{nom}" class="inputbox">
			<label><b>Email *</b></label>
			{erreurmail}
			<input type="email" name="email" value="{email}" class="inputbox">
			<label><b>Il tuo messaggio *</b></label>
			{erreurmessage}
			<textarea class="textareabox" name="message">{message}</textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Inviare" class="blueBtn">
		</form>
	</div>
</div>
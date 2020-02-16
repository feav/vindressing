<div class="container topMargin" style="margin-bottom: 90px;">
	{errormessage}
	<H1>Signaler l'annonce</H1>
	Nous vous proposons de sélectionner le motif de votre demande dans le menu ci-dessous. Vous y trouverez un texte d'information qui répond aux cas fréquents rencontrés par nos utilisateurs et clients.
	<br><br>
	Votre demande de signalement sur l'annonce sera examiner dans les plus bref délais par nos équipes, il est possible que l'examen de votre demande prenne 6 jour maximum.
	<div class="signaler-box">
		<form method="POST">
			<label><b>Motif * :</b></label><br><br>
			{errormotif}
			<input type="radio" name="motif" value="fraude"> Fraude
			<input type="radio" name="motif" value="doublon"> Doublon
			<input type="radio" name="motif" value="mauvaise_categorie"> Mauvaise catégorie
			<input type="radio" name="motif" value="deja_vendu"> Déjà vendu
			<input type="radio" name="motif" value="discrimination"> Discrimination
			<input type="radio" name="motif" value="chien_chat"> Chien et chat
			<input type="radio" name="motif" value="contrefacon"> Contrefaçon
			<input type="radio" name="motif" value="autre_abus"> Autre abus<br><br>
			<label><b>Nom *</b></label>
			{erreurnom}
			<input type="text" name="nom" value="{nom}" class="inputbox">
			<label><b>Email *</b></label>
			{erreurmail}
			<input type="email" name="email" value="{email}" class="inputbox">
			<label><b>Votre message *</b></label>
			{erreurmessage}
			<textarea class="textareabox" name="message">{message}</textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Envoyez" class="blueBtn">
		</form>
	</div>
</div>
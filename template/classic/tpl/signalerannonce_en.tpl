<div class="container topMargin" style="margin-bottom: 90px;">
	{errormessage}
	<H1>Report Ad</H1>
	We suggest you select the reason for your request in the menu below. You will find a text of information which answers the frequent cases met by our users and customers.
	<br><br>
	Your request for reporting on the ad will be reviewed as soon as possible by our teams, it is possible that the review of your application takes up to 6 days.
	<div class="signaler-box">
		<form method="POST">
			<label><b>Reason for the request * :</b></label><br><br>
			{errormotif}
			<input type="radio" name="motif" value="fraude"> Fraud
			<input type="radio" name="motif" value="doublon"> Duplicate
			<input type="radio" name="motif" value="mauvaise_categorie"> Bad category
			<input type="radio" name="motif" value="deja_vendu"> Already sold
			<input type="radio" name="motif" value="discrimination"> Discrimination
			<input type="radio" name="motif" value="chien_chat"> Dog and cat
			<input type="radio" name="motif" value="contrefacon"> Forgery
			<input type="radio" name="motif" value="autre_abus"> Other abuse<br><br>
			<label><b>Name *</b></label>
			{erreurnom}
			<input type="text" name="nom" value="{nom}" class="inputbox">
			<label><b>Email *</b></label>
			{erreurmail}
			<input type="email" name="email" value="{email}" class="inputbox">
			<label><b>Your message *</b></label>
			{erreurmessage}
			<textarea class="textareabox" name="message">{message}</textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Send" class="blueBtn">
		</form>
	</div>
</div>
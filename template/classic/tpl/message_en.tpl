<div class="container topMargin" style="margin-bottom: 90px;">
	<div class="mega-block">
		<div class="big-block-gauche">
		{msg_valid}
		<H1>Send a message to "{username}"</H1>
		<p>
		Remember to include your phone number so that the advertiser can contact you easily. Any advertising or solicitation spamming will be eliminated.
		</p>
		<form method="POST">
			<label><b>Name * :</b></label>
			<input type="text" name="nom" class="inputbox">
			<label><b>Email * :</b></label>
			<input type="email" name="email" class="inputbox">
			<label><b>Message * :</b></label>
			<textarea name="message" class="textareabox"></textarea>
			<input type="hidden" name="md5" value="{md5}">
			<input type="hidden" name="action" value="1">
			<input type="submit" value="Send" class="blueBtn">
		</form>
		</div>
	</div>
</div>
<div class="container topMargin" style="margin-bottom: 90px;">
		<div class="nav">
			{navigation}
		</div>
		<div class="mega-block">
			<div class="big-block-gauche">
				<h1>{titre}</h1>
				{urgente}
				<div class="big-block-photo">
					<img id="big-image-block" src="{bigimage}">
				</div>
				<div class="block-selection-image">
				{all_image}
				</div>
				<div class="mise-en-ligne">
					<div class="mise-en-ligne-text">{date_mise_en_ligne}</div>
					<div class="nombre-de-vue"><img src="{url_script}/images/view-icon.png" title="Number of views" alt="Number of views">{nombre_de_vue}</div>
				</div>
				<div class="user-mise-en-ligne">{utilisateur}</div>
				<div class="information">
					<div class="information-title">
					Price
					</div>
					<div class="other-information">
					{prix}
					</div>
					<div class="information-title">
					Town
					</div>
					<div class="other-information">
					{codepostal} - {ville}
					</div>
				</div>
				<div class="block-description">
					<b>Description :</b>
					<br><br>
					{description}
				</div>
				<hr>
				<div class="block-signaler">
					<div class="image-signaler">
						<img src="{url_script}/images/signaler-icon.png" alt="Report an ad">
					</div>
					<div class="link-signaler">
						<a href="{signaler_link}">Report Ad</a>
					</div>
				</div>
				<div class="block-social-share">
					{sharing_social}
				</div>
			</div>
			<div class="big-block-droit">
				<div class="username-block-droit"><b>{utilisateur}</b></div>
				{telephone}
				<div class="send-message-block">
					<a href="javascript:window.print()" class="blueBtn print-icon"> Print ad</a>
				</div>
				<div class="send-message-block">
					<a href="{send_message_link}" class="blueBtn message-icon">Send a message</a>
				</div>
			</div>
			<div class="big-block-droit">
				<h4>Ads</h4>
				{publicite_left}
			</div>
		</div>
		<script>
		var oldid = 0;
		function showBigImage(url,x)
		{
			$('#id-'+oldid).css('border','0px solid #000000');
			$('#id-'+x).css('border','2px solid rgb(255, 135, 0)');
			$('#big-image-block').prop('src',url);
			oldid = x;
		}
		
		function showNumberPhone()
		{
			$('#btnPhone').css('display','none');
			$('#phone-number').css('display','block');
		}
		</script>
</div>
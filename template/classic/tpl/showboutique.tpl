<div class="container topMargin" style="margin-bottom: 90px;">
		<div class="nav">
			{navigation}
		</div>
		<H1>{titre}</H1>
		<div class="mega-block">
			<div class="boutique-block">
				<div class="boutique-logo">
					<img src="{logo}">
				</div>
				<div class="boutique-complement-info">
					<div class="boutique-adresse"><i class="fas fa-map-marker-alt"></i> {adresse}</div>
				</div>
			</div>
			<div class="boutique-block">
				<div class="boutique-complement-info">
				{description}
				<div class="btn-website-boutique">{website}</div>
				</div>
			</div>
		</div>
		<div class="mega-block">
			<div class="block-simple">
			{block_offre}
			</div>
		</div>
		{paging}
</div>
<script>
function addFavoris(md5)
{
	document.location.href = '{url_script}/addfavoris.php?md5='+md5;
}

function removeFavoris(md5)
{
	document.location.href = '{url_script}/removefavoris.php?md5='+md5;
}
</script>
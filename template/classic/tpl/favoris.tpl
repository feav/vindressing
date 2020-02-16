<div class="container topMargin" style="margin-bottom: 90px;">
	<H1>Mes Favoris</H1>
	<p>
	Retrouver depuis cette page toute vos annonces ajouter en favoris.
	</p>
	{favoris_item}
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
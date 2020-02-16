<div class="container topMargin">
	<div class="ads728">
		{publicite_offre}
	</div>
	<H1>{titleannonce}</H1>
	{block_resultat}
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
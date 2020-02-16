<div class="container topMargin">
	<div class="ads728">
		{publicite_offre}
	</div>
	<H1>{titleannonce}</H1>
	<div class="search-section-box">
		<div class="search-input">
			<form>
				<input class="searchtext" type="text" name="searchtext" value="{searchtext}" placeholder="Que recherchez-vous ?">
				<select name="categorie" id="categorie" onchange="checkCategorie();" class="categorybox">
				{categorie}
				</select>
				{departement}
				{prix}
				<input type="submit" value="Rechercher" class="blueBtn nodisplay searchIcon">
				<div id="filtre">
				</div>
			</form>
		</div>
	</div>
	<script>
	function checkCategorie()
	{
		var categorie = $('#categorie').val();
		$('#filtre').load('module/checkOffre.php?categorie='+categorie);
	}
	</script>
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
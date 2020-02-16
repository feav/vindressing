<div class="container topMargin">
	<div class="ads728">
		{publicite_top}
	</div>
	<H1>{titleannonce}</H1>
	<div class="search-section-box">
		<div class="search-input">
			<form>
				<input class="searchtext" type="text" name="searchtext" value="{searchtext}" placeholder="Какво търсите?">
				<select name="categorie" id="categorie" onchange="checkCategorie();" class="categorybox">
				{categorie}
				</select>
				{departement}
				{prix}
				<input type="submit" value="търсене" class="blueBtn nodisplay searchIcon">
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
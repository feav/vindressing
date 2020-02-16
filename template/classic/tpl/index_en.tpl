<div id="content">
	
</div>
<div class="container">
	<div class="ads728">
		{publicite_top}
	</div>
</div>
<div class="container topMargin">
	<div class="englobehomebox">
		<div class="homeboxleft">
			Find the right deal among <br>{count_annonce} ads on PAS Script.
			<form method="POST" action="offre.php">
				<input class="inputClass" type="text" name="searchtext" value="" placeholder="What are you looking for ?">
				<select name="categorie" class="selectClass">
				{all_categorie}
				</select>
				{all_departement}
				<input type="submit" value="Search" class="blueBtn cent searchIcon">
			</form>
			<div class="deposer-annonce-box">
				<a href="deposer-une-annonce.php" class="blueBtn cent orange-btn">Place an ad</a>
			</div>
		</div>
		<div class="homeboxcenter">
			{map}
		</div>
		<div class="homeboxrigth">
			{region}
		</div>
	</div>
</div>
<div class="categorieshow">
{categorie}
</div>
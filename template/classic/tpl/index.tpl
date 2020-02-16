<div id="content">
	
</div>
{habillage}
<div class="container">
	<div class="ads728">
	</div>
</div>
<div class="container topMargin">
	<div class="englobehomebox">
		<div class="homeboxleft">
			Trouver la bonne affaire parmi les <br>{count_annonce} annonces	sur vindressing.fr
			<form method="POST" action="offre.php">
				<input class="inputClass" type="text" name="searchtext" value="" placeholder="Que recherchez-vous ?">
				<select name="categorie" class="selectClass">
				{all_categorie}
				</select>
				{all_departement}
				<input type="submit" value="Rechercher" class="blueBtn cent searchIcon">
			</form>
			<div class="deposer-annonce-box">
				<a href="deposer-une-annonce.php" class="blueBtn cent orange-btn">Deposer une annonce</a>
			</div>
		</div>
		<div class="homeboxcenter">
			{map}
		</div>
		<div class="homeboxrigth">
			{region}
		</div>
	</div>
        <div class="ads728">
        </div>
</div>
<div class="categorieshow">
{categorie}
</div>
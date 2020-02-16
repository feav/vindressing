<div class="container topMargin" style="margin-bottom: 90px;">
	{validation_annonce}
	<H1>Mes Annonces</H1>
	<p>Retrouvez l'ensemble de vos annonces postées sur le site, vous pouvez les supprimer une fois vendues.</p>
	{block}
</div>
<script>
function showOption(id)
{
	if($('#more_option_action-'+id).css('display') == 'none')
	{
		$('#more_option_action-'+id).css('display','block');
	}
	else
	{
		$('#more_option_action-'+id).css('display','none');
	}
}
</script>
<div class="container topMargin" style="margin-bottom: 90px;">
	{validation_annonce}
	<H1>My Ads</H1>
	<p>Find all of your ads posted on the site, you can delete them once sold.</p>
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
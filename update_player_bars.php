<?php

	require_once('config.php');
    login_check();
	
	$_SESSION['player']->updateLocally();
	$_SESSION['player']->drawJourney();
	$_SESSION['player']->drawMail();
	$_SESSION['player']->drawGold();
	$_SESSION['player']->drawCrystals();
	$_SESSION['player']->drawHP("mainHP", "");
	$_SESSION['player']->drawMP("mainMP", "");
	$_SESSION['player']->drawEXP("mainEXP", "");
	
	//Last update is saved locally, in number format
	if(is_numeric($_SESSION['player']->last_update))
	{
		$last = $_SESSION['player']->last_update;
	}
	//Last update was downloaded from DB, in time format
	else
	{
		$last = strtotime($_SESSION['player']->last_update);
	}
	
?>

<script>
	
	journeyCountdown();
	
	function journeyCountdown()
	{
		var journey = <?php echo json_encode($_SESSION['player']->journey); ?>;
		
		if(journey != null)
		{
			var journey_until = <?php echo json_encode(date("Y-m-d H:i:s", $_SESSION['player']->journey_until)); ?>;
			var journey_started_seconds = <?php echo json_encode($_SESSION['player']->journey_started); ?>;
			var journey_until_seconds = <?php echo json_encode($_SESSION['player']->journey_until); ?>;
			var journeyFoto = "#" + journey + "Foto";
		
			$("#journeyTekst").countdown(journey_until, function(event) {
				$(this).html(event.strftime('%H:%M:%S'))
			}).on('finish.countdown', function(event) {
				//TODO Load combat when countdown finishes
				//$("#divMainOkno").load('walka.php');
			});
		}
	}
	
</script>
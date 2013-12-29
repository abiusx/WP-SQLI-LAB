<?php

	if ( !isset($wp_did_header) ) 
	{
		$wp_did_header = true;
		require_once('../../../wp-load.php' );
	}
	$cronJobName = $_REQUEST['job_name'];
	
	switch( $cronJobName )
	{
		case "swarmWinner":
			$fbmo->checkTippingPointForSwarm();
	}
?>
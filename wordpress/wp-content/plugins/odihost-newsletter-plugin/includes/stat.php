<?php
	
	global $wpdb;
	$table_newsletter = $wpdb->prefix . "odi_newsletter";

	$newsletter = $wpdb->get_results("select * from $table_newsletter where id=".$_REQUEST["id"]);
	
	if(count($newsletter)==0)
	{
		echo "Invalid request";
		die;
	}
	
	echo "<h1>Stats for newsletter: ".$newsletter->newslettertitle."</h1>"; 
	$stat = $wpdb->get_row("select * from newsletterstat where newsletterstatnewsletterid=".$_REQUEST["id"]);
?>
<table  class="widefat fixed">
	<tr>
		<td>Sent date:</td>
		<td><?php echo $stat->newsletterstatsenddate;?></td>
	</tr>
	<tr>
		<td>Sent to:</td>
		<td><?php echo $stat->newsletterstatsent;?> people</td>
	</tr>
	<tr>
		<td>Opened by:</td>
		<td><?php 
			$newsletterstatopened = $stat->newsletterstatopened;
			$newsletterstatopened = split(",",$newsletterstatopened);
		echo count($newsletterstatopened)-1;?> people</td>
	</tr>
	<tr>
		<td>Most clicked url:</td>
		<td><?php 
			$urls = $wpdb->get_results("select *, count(*) as total from newsletterclickstat where newsletterclickstatnewsletterid = ".$_REQUEST["id"] . " order by total");
			foreach($urls as $url)
			{
				echo "<li><a href='".$url->newsletterclickstaturl."'>".$url->newsletterclickstaturl."</a> - ". $url->total."</li>";
			}
		?>
	</td>
	</tr>

</table>
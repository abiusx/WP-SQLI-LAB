<?php

	include ("news.php");
if($_GET["id"] != "" || $_GET["task"] == "edit")
{
	include("email_schedule_form.php");
}
else
{
	if($_REQUEST["email_schedule_id"] != "")
	{
		//delete
		global $wpdb;
		$table_email_schedule = $wpdb->prefix . "newsletter_email_schedule";
		$wpdb->query("delete from $table_email_schedule where id = ". $_REQUEST["email_schedule_id"]);
		echo "<h2>Email schedule deleted</h2>";
	}

	$url = get_bloginfo('wpurl') . '/wp-admin/admin.php?page=odihost_newsletter_email_schedule';	

?>
<h2>Email Schedule</h2>
<p><a href="<?php echo get_option("siteurl"). "/wp-admin/admin.php?page=odihost_newsletter_email_schedule";?>&id=0">Add new</a></p>
<table class="widefat">
<thead>    
<tr>
<td scope="col">ID</td>
<td scope="col">Subject</td>
<td scope="col">Message</td>
<td scope="col">Status</td>
<td scope="col">Day Sent</td>
<td scope="col"></td>
</tr>
</thead>
<tbody>
<?php

	global $wpdb;
	$table_email_schedule = $wpdb->prefix . "newsletter_email_schedule";
	$email_schedules = $wpdb->get_results("select * from $table_email_schedule"); 
	$url = $url . '&amp;email_schedule_id=';
	$offset=$_GET[offset];
	odihostcheckValid($offset);
	
	if($offset =='')
		$offset = 0;
		
	$limit = 50;
	
	foreach($email_schedules as $email_schedule)
	{
				
		if ($email_schedule_no&1) {
			echo "<tr class=\"alternate\">";
		} else {
			echo "<tr>";
		}
		$email_schedule_no=$email_schedule_no+1;
		echo "<td>$email_schedule->id</td>";
		echo "<td>" . $email_schedule->subject . "</td>";
		echo "<td>" . $email_schedule->message . "</td>";
		echo "<td>";  if($email_schedule->status == "1") echo "Active"; else echo "Not active"; echo "</td>";
		echo "<td>" . $email_schedule->daysent . "</td>";
		echo "<td><a href=\"$url$email_schedule->id&task=edit\">Edit</a> | <a href=\"$url$email_schedule->id&task=delete\" onclick=\"if(confirm('Are you sure you want to delete email_schedule witd ID $email_schedule->id?')) return; else return false;\">Delete</a></td>";
		echo "</tr>";
	}

?>
</tbody>
</table>
<?php } ?>
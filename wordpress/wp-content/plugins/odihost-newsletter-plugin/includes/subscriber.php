<?php

	global $wpdb;
	include ("news.php");

	$where = $_GET['status'];
	if($where != "")
		$where = "where status = ".$where;
		
	$table_users = $wpdb->prefix . "newsletter_users";

			
			
	// if $_GET['user_id'] set tden delete user from list
	if (isset($_GET['user_id'])) {
		$user_id = $_GET['user_id'];

		$delete = "update " . $table_users .
				" set status = 3 WHERE id = '" . $user_id . "'";
		$result = $wpdb->query($delete);

		echo '<div id="message" class="updated fade"><p><strong>';
		_e('User deleted.', 'wpnewsletter_domain');
		echo '</strong></p></div>';
	}
		$url = get_bloginfo('wpurl') . '/wp-admin/admin.php?page=odihost_newsletter_subscriber';
	?>
	<h1>
	<?php 
	switch($_REQUEST["status"])
	{
		case 1:
			echo "Opt-in ";
			break;
		case 2:
			echo "Not Opt-in ";
			break;
		case 3:
			echo "Deleted ";
			break;
	}
	?>Email List</h1>
<a href="<?php echo $url;?>">Show all</a> - <a href="<?php echo $url;?>&status=1">Show Only Opt-in</a> - <a href="<?php echo $url;?>&status=2">Show Not Opt-in</a> - <a href="<?php echo $url;?>&status=3">Show Removed user</a><br/><br/>

	<?php
	if ($users = $wpdb->get_results("SELECT * FROM $table_users $where ORDER BY `id` DESC")) {
		$user_no=0;
?>

<table class="widefat">
<thead>    
<tr>
<td scope="col">ID</td>
<td scope="col">Date Join</td>
<td scope="col">Opted-in</td>
<td scope="col">IP</td>
<td scope="col">Name</td>
<td scope="col">E-mail</td>
<td scope="col">Action</td>
</tr>
</thead>
<tbody>
<?php
		$url = $url . '&amp;user_id=';
		$offset=$_GET[offset];
		odihostcheckValid($offset);
		
		if($offset =='')
			$offset = 0;
			
		$limit = 50;
		
		$pagenumber =intval(count($users)/$limit);
		if(count($users)%$limit)
		{
			$pagenumber++;
		}

		//paging
		echo("Page: ");
		for($i=1;$i<=$pagenumber;$i++)
		{
			$newpage=$limit*($i-1);

			if($offset!=$newpage)
			{
				echo "[<a href='admin.php?page=odihost_newsletter_subscriber&type=".$_GET['type']. "&offset=".$newpage."'>$i</a>]";
			}else
			{
				echo "[$i]";
			}
		}

		for($i=$offset;$i<$offset+$limit;$i++)
		{
			$user = $users[$i];
			//check if we need to print
			if(!$user->joindate)
				continue;
					
			if ($user_no&1) {
				echo "<tr class=\"alternate\">";
			} else {
				echo "<tr>";
			}
			$user_no=$user_no+1;
			echo "<td>$user->id</td>";
			echo "<td>" . $user->joindate . "</td>";
			echo "<td>";
			if($user->status == 1)
				echo "Yes";
			else if($user->status == 0)
				echo "No";
			else if ($user->status == 3)
				echo "Removed";
				
			echo "</td>";
			echo "<td>$user->ip</td>";
			echo "<td>$user->name</td>";
			echo "<td>$user->email</td>";
			echo "<td><a href=\"$url$user->id\" onclick=\"if(confirm('Are you sure you want to delete user witd ID $user->id?')) return; else return false;\">Delete</a></td>";
			echo "</tr>";
		}

		//paging
?>
</tbody>
</table>
<?php
		echo("Page: ");
		for($i=1;$i<=$pagenumber;$i++)
		{
			$newpage=$limit*($i-1);

			if($offset!=$newpage)
			{
				echo "[<a href='admin.php?page=odihost_newsletter_subscriber&type=".$_GET['type']. "&offset=".$newpage."'>$i</a>]";
			}else
			{
				echo "[$i]";
			}
		}
?>


<?php 
}
?>
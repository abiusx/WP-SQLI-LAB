<?php

	//export function is in newsletter.php
	
	if($_REQUEST["process"] == "import")
	{
		global $wpdb;
		$table_users = $wpdb->prefix . "newsletter_users";

		//upload photo
		$file = $_FILES['openfile'];
		$dir = str_replace("includes","uploads\\",dirname(__FILE__));
		$target_path = $dir . "file.txt"; 
		if($file["error"]== 0 && $file["name"]!= "")
		{	
			if(!move_uploaded_file($file["tmp_name"], $target_path))
				echo "Failed uploading file import";
		}
		$file= get_option("siteurl")."/wp-content/plugins/odihost-newsletter-plugin/uploads/file.txt";
		$handle = fopen($target_path, "r");
		if(filesize($target_path) >0)
		{
			//$contents = fread($handle, filesize($target_path));
			$counter =0;
			 while (!feof($handle)) {
				$buffer = fgets($handle);
				
				if($buffer != "")
				{
					$insert = "INSERT INTO `$table_users` (`joindate`, `ip`, `email`, `name`,`status`) " .
				"VALUES (now(),'127.0.0.1','" . $buffer . "','',1)";
					$result = $wpdb->query($insert);
					$counter++;
				}
			}
			
			echo "<h2>$counter users added</h2>";
			fclose($handle);
		}
	}
?>
<h2>Export</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="process" value="export" />
<input type="submit" value="Export">
</form>
<h2>Import</h2>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="process" value="import" />
	File Name:<input type="file" name="openfile" value="" size="50"/><input type="submit" value="Import"/><br/>You can see the format file <a href="http://odihost.com/doc/format.txt" target="_blank">here</a>. Please backup your database first.</form>
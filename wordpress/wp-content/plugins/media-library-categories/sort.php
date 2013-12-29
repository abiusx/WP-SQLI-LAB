<div class='wrap'>
<?php
global $wpdb;



if (file_exists("./wp-config.php")){include("./wp-config.php");}
		elseif (file_exists("../wp-config.php")){include("../wp-config.php");}
		elseif (file_exists("../../wp-config.php")){include("../../wp-config.php");}
		elseif (file_exists("../../../wp-config.php")){include("../../../wp-config.php");}
		elseif (file_exists("../../../../wp-config.php")){include("../../../../wp-config.php");}
		elseif (file_exists("../../../../../wp-config.php")){include("../../../../../wp-config.php");}
		elseif (file_exists("../../../../../../wp-config.php")){include("../../../../../../wp-config.php");}
		elseif (file_exists("../../../../../../../wp-config.php")){include("../../../../../../../wp-config.php");}
		elseif (file_exists("../../../../../../../../wp-config.php")){include("../../../../../../../../wp-config.php");}

$db_host = DB_HOST; 
$db_user = DB_USER; 
$db_pass = DB_PASSWORD; 
$db_name = DB_NAME; 
			
			
$connect = mysql_connect( $db_host, $db_user, $db_pass ) or die( mysql_error() ); 
$connection = $connect; 

mysql_select_db( $db_name, $connect ) or die( mysql_error() ); 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   START PROPERTIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			



$optionsName = 'mc_options';
$options = get_option($optionsName);
$insert_sort = '';
$termid=$_GET['termid'];
$taxonomyid=$wpdb->get_var($wpdb->prepare("SELECT term_taxonomy_id FROM " . $table_prefix . "term_taxonomy where term_id=$termid"));

$where = '';
if($termid)
{
	$where .= " && tt.term_id=".$termid;
} 

//---- This is in place for version before 1.0.6 -------------------
$currentSort='';
if($options['mc_media_category_'.$termid.'_sort'] !=null ||$options['mc_media_category_'.$termid.'_sort'] =='')
{
	$currentSort=$options['mc_media_category_'.$termid.'_sort'];
}
//---- END -------------------------------------------------------
 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   END PROPERTIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   START EVENT HANDLERS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
 if ($_POST && $_POST[act]=="sort" && $_POST[sortinput] && $_POST[sortinput]!='') {
		
		$ids = explode(",", $_POST[sortinput]);
		$i=1;
		foreach ($ids as $id)
		{
			//update term_taxonomy with sort
			$updatequery = "UPDATE " . $table_prefix . "term_relationships set term_order=$i where object_id=$id AND term_taxonomy_id=$taxonomyid;";
			$updateresult = mysql_query($updatequery); 
			if(!updateresult)
			{ 
				print "<br>Error! ".mysql_error()."<br><br>"; 
			}
			$i+=1;
		}
		print "<div class='highlight'>Sort Saved Successfully $view_link</div> "; 
		
} 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////   END EVENT HANDLERS
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

print "
<link media='all' type='text/css' href=\"/wp-admin/css/colors-fresh.css\" id=\"colors-css\" rel=\"stylesheet\">
<form>
	<table cellpadding='0px' cellspacing='0px' width='100%'>
		<tr>
			<td>
				<h2>Sort Media Category Files <a class='button add-new-h2' href='admin.php?page=$rl_dir/view.php'>View Media Categories</a></h2>
			</td>
			
		</tr>
		<tr>
			<td align='right'>
				
			</td>
		</tr>
	</table>
</form>";



print "<form name='itemsForm' method='post'>";
		//---- This is in place for version before 1.0.6 -------------------
		if($currentSort!='')
		{
			$ids = explode(",", $currentSort);
			$i=1;
			foreach ($ids as $id)
			{
				//update term_taxonomy with sort
				$updatequery = "UPDATE " . $table_prefix . "term_relationships set term_order=$i where object_id=$id AND term_taxonomy_id=$taxonomyid;";
				$updateresult = mysql_query($updatequery); 
			
				$i+=1;
			}
			//remove old sort
			$options['mc_media_category_'.$termid.'_sort']='';
			update_option($optionsName, $options);
		}
		//---- END -------------------------------------------------------
		
		
		$query = 	"SELECT p.*, a.term_order FROM " . $table_prefix . "posts p
					inner join " . $table_prefix . "term_relationships a on a.object_id = p.ID
					inner join " . $table_prefix . "term_taxonomy ttt on ttt.term_taxonomy_id = a.term_taxonomy_id
					inner join " . $table_prefix . "terms tt on ttt.term_id = tt.term_id
					where ttt.taxonomy='media_category' $where order by a.term_order asc;";
 
		$results = mysql_query($query); 
		
		
		 
print "
			

<br>
<input type='hidden' id='sortinput' name='sortinput' value='' />
<table id='table-1' class='widefat' cellspacing=0>
    <thead>
        <tr class='nodrop nodrag'>
			<th>File ID</th>
			<th>Name</th>
			<th>File</th>
        </tr>
    </thead>";
	
	
	
	
	if ($results){ 
		$num_rows = mysql_num_rows($results);
		if($num_rows>0)
		{
			$i=1;
			while ($row = mysql_fetch_array($results)) { 
						
				$label = $row['post_title'];
				$id = $row['ID'];
				$fileUrl = $row['guid'];
				$mime = $row['post_mime_type'];
				
				$thumbnailSize = 'thumbnail';
				$thumb = wp_get_attachment_image_src( $id, 'thumbnail' ); 
				
				$currentOrderBy = $row['sortorder'];
				
				if($mime=='image/jpeg')
				{
					print "
					<tr id='$id' style='background-color:$bgcol'>
						<td>$id</td>
						<td>$label</td>
						<td><img src='".$thumb[0]."' /></td>
					</tr>";
				}
				else
				{
					print "
					<tr id='$id' style='background-color:$bgcol'>
						<td>$id</td>
						<td>".$fileUrl."</td>
					</tr>";
				}
				
				$i+=1;
				
				
			}
		}
		}else { 
		echo "Error!".mysql_error()."<br><br>".$query; 
		} 
		mysql_close();
	
	
	
	
	

    
    
print " <tfoot>
        <tr class='nodrop nodrag'>
			<th>File ID</th>
			<th>Name</th>
			<th>File</th>
        </tr>
    </tfoot>
</table>
<br />
<input type='submit' value='Save Sort' class='button'>
<input name='act' type='hidden' value='sort'><br>";

print "</form>";

	
?>
</div>
<?php

$label = "Add";
$term_id=0;
$term=null;

if ($_GET[edit]) {
	$term_id=$_GET[edit];
	$label="Edit";	
	
	$term = get_term($term_id, 'media_category');
	
	
}



print "
<div class='wrap'>
<h2>$label Media Category</h2><br>";

global $wpdb;
//initialize_variables();


if($term_id==0)
{
	if ($_POST[category] && $_GET[mode]!="pca") {
		
		$currentCategory = $_POST[category];
		if($currentCategory&&$currentCategory!='')
		{
			wp_insert_term(
			  $_POST[category], // the term 
			  'media_category', // the taxonomy
			  array(
				'description'=> $_POST[content],
				'slug' => $_POST[slug],
				'parent'=> $_POST[parentCat]
			  )
			);
		
		
		
			print "<div class='highlight'>Successful Addition $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; //header("location:$_SERVER[HTTP_REFERER]");
		
		}
		else
		{
			print "<div class='highlight'>Category is a required field.</div>";
		
		}
		
	}
	else
	{
		print "<div class='highlight'>Category is a required field.</div><br><br>";

	}
}
else{

	if ($_POST[category] && $_GET[mode]!="pca") {
		
		$currentCategory = $_POST[category];
		if($currentCategory&&$currentCategory!='')
		{
			wp_update_term(
			  $term_id, // the term 
			  'media_category', // the taxonomy
			  array(
				'name'=> $_POST[category],
				'description'=> $_POST[content],
				'slug' => $_POST[slug],
				'parent'=> $_POST[parentCat]
			  )
			);
		
			$term = get_term($term_id, 'media_category');
		
			print "<div class='highlight'>Successful Update $view_link</div> <!--meta http-equiv='refresh' content='0'-->"; //header("location:$_SERVER[HTTP_REFERER]");
		
		}
		else
		{
			print "<div class='highlight'>Category is a required field.</div>";
		
		}
		
	}
	else if($_POST)
	{
		print "<div class='highlight'>aCategory is a required field.</div><br><br>";

	}

}




	
$base=get_option('siteurl');

print "


<table cellpadding='10px' cellspacing='0' style='width:100%' class='manual_add_table'><tr>
<td style='/*border-right:solid silver 1px;*/ padding-top:0px;' valign='top'>

<form name='manualAddForm' method=post>
	<table cellpadding='0' class='widefat'>
	<tr>
		<td>
		
			Category*<br><input name='category' size=40 ".(($term!=null)?"value='".$term->name."'":"")."><br>
			Slug<br><input name='slug' size=40 ".(($term!=null)?"value='".$term->slug."'":"")."><br>
			Parent<br>
			<select name='parentCat'>
					<option> -- Select Parent -- </option>
				";
				
				print $mc_var->get_category_hierarchical_selectoptions((($term!=null)?$term->parent:0));
			print "</select>
			
			<br><br>
			Description<br>";
			?>
<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
<?php the_editor(stripslashes($term->description),'content','content', false); ?>
</div>
<?php
	print "		<br><br>
			<input type='submit' value='$label Category' class='button'> 
			<a href=\"admin.php?page=$rl_dir/view.php\" class=\"button\">Cancel</a>
		
		</td>
	</tr>
	</table>
</form>

</td>
<td style='/*border-right:solid silver 1px;*/ padding-top:0px;' valign='top'>
</td>
<td valign='top' style='padding-top:0px;'>
</td>
</tr>
</table>
</div>";
?>


<?php

?>
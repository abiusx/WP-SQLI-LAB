<?php
/**
 * Plugin Name: PureHTML
 * Version: 1.0.0
 * Description: Allows HTML in the HTML window without it being stripped in the visual editor.  Allows the insertion of pre-saved html
 * Author: Hit Reach
 * Author URI: http://www.hitreach.co.uk/
 * Plugin URI: http://www.hitreach.co.uk/wordpress-plugins/pure-html/
 */

//table name .$wpdb->prefix."pureHTML_functions"
add_shortcode('pureHTML','pureHTML_handler');
add_shortcode('purehtml','pureHTML_handler');
add_shortcode('PUREHTML','pureHTML_handler');

add_action('admin_menu', 'pure_menu');
register_activation_hook(__FILE__, 'pureHTML_activate');
add_filter('pureHTML_handler','do_shortcode');
global $dbVersion; $dbVersion = "1.0.0";

function pureHTML_activate(){
	global $wpdb;
	global $dbVersion;
	$options = get_option("pureHTML_options");
	$installedVersion = $options['dbVersion'];
	$show404 = 1;
	$fourohfourmsg = 0;
	if(isset($options['show404'])){
		$show404 = $options['show404'];
	}
	if(isset($options['fourohfourmsg'])){
		$fourohfourmsg = $options['fourohfourmsg'];
	}
	if($installedVersion != $dbVersion){
$sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."pureHTML_functions(
id int NOT NULL AUTO_INCREMENT,
name varchar(100) NOT NULL,
function text NOT NULL,
PRIMARY KEY(id)
);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	}

	$options = array("show404" => $show404,"fourohfourmsg" => $fourohfourmsg, "dbVersion" => $dbVersion);
	update_option("pureHTML_options", $options);
}

function pureHTML_handler($args, $content=null){
	$options = get_option("pureHTML_options");
	$show404 = $options['show404'];
	global $wpdb;
	$fourohfourmsg = $options['fourohfourmsg'];
	if($fourohfourmsg != 0){
		$fourohfourmsg = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."pureHTML_functions WHERE id = '".$fourohfourmsg."';");
		$fourohfourmsg = $fourohfourmsg[0]->function;
	}
	else{
		$fourohfourmsg = '<span style="font-weight:bold; color:red">Error 404: Function Not Found</span>';
	}
	
	define("PUREHTMLVERSION","1.0.0");
	extract(shortcode_atts(array('debug' => 0,'id' =>0), $args));
	if($args['debug'] == 1){
		error_reporting(E_ALL); 
		ini_set("display_errors","1");
	}
	if($args['id'] == 0){
		/*CONTENT REPLACEMENT*/
		$content =(htmlspecialchars($content,ENT_QUOTES));
		$content = str_replace("&amp;#8217;","'",$content);
		$content = str_replace("&amp;#8216;","'",$content);
		$content = str_replace("&amp;#8242;","'",$content);
		$content = str_replace("&amp;#8220;","\"",$content);
		$content = str_replace("&amp;#8221;","\"",$content);
		$content = str_replace("&amp;#8243;","\"",$content);
		$content = str_replace("&amp;#039;","'",$content);
		$content = str_replace("&#039;","'",$content);
		$content = str_replace("&amp;#038;","&",$content);
		$content = str_replace("&amp;lt;br /&amp;gt;"," ", $content);
		$content = htmlspecialchars_decode($content);
		$content = str_replace("<br />"," ",$content);
		$content = str_replace("<p>"," ",$content);
		$content = str_replace("</p>"," ",$content);
		$content = str_replace("[br/]","<br/>",$content);
		$content = str_replace("\\[","&#91;",$content);
		$content = str_replace("\\\\]","\\&#93;",$content);
		$content = str_replace("\\]","&#93;",$content);
		$content = str_replace("[","<",$content);
		$content = str_replace("]",">",$content);
		$content = str_replace("&#91;",'[',$content);
		$content = str_replace("&#93;",']',$content);
		$content = str_replace("&gt;",'>',$content);
		$content = str_replace("&lt;",'<',$content);
	} 
	else{
		$id = $args['id'];
		$sql = "SELECT function FROM ".$wpdb->prefix."pureHTML_functions WHERE id='".$id."'";
		$res = $wpdb->get_results($wpdb->prepare($sql));
		#function not found
		if(sizeof($res) == 0){
			if($show404 == 1){
				$content = $fourohfourmsg;
			}
		}
		else{
			$res = $res[0]->function;
			$strposopenphp = explode("[php",$res);
			if(sizeof($strposopenphp) > 1 && function_exists("php_handler")){
				foreach($strposopenphp as $row){
					$otherClose = strpos($row, "[/php]");
					if($otherClose != ""){
						$otherClose = $otherClose +6;
						$theCode = substr($row,0,$otherClose);
						$theCode =  "[php".$theCode;
						$r = do_shortcode($theCode);
						$rest = strlen($row);
						$rest = substr($row,$otherClose,$rest);
						$row = $r . $rest;
					}
					$res .= $row;
				}
			}
			$strposopenjs = explode("[js", $res);	
			if(sizeof($strposopenjs) > 1 && function_exists("js_handler")){
				foreach($strposopenjs as $row){
					$otherClose = strpos($row, "[/js]");
					if($otherClose != ""){
						$otherClose = $otherClose +6;
						$theCode = substr($row,0,$otherClose);
						$theCode =  "[js".$theCode;
						$r = do_shortcode($theCode);
						$rest = strlen($row);
						$rest = substr($row,$otherClose,$rest);
						$row = $r . $rest;
					}
					$res .= $row;
				}
			}
			$content = $res;
			}						
	}
	ob_start();
	echo $content;
	if($args['debug'] == 1){
		$content =(htmlspecialchars($content,ENT_QUOTES));
		echo ("<pre>".$content."</pre>");
	}
	$returned = ob_get_clean();
	return $returned;
}
function pure_menu(){
	add_menu_page( "pureHTML", "pureHTML", "edit_posts", "pure-html-menu", "pure_functions");
}
function pure_functions(){
	$options = get_option("pureHTML_options");
	$show404 = $options['show404'];
	global $wpdb;
	$fourohfourmsg = $options['fourohfourmsg'];
	$fourohfourmsg_id = $options['fourohfourmsg'];
	if($fourohfourmsg != 0){
		$fourohfourmsg = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."pureHTML_functions WHERE id = '".$fourohfourmsg."';");
		$fourohfourmsg = $fourohfourmsg[0]->function;
	}
	else{
		$fourohfourmsg = '<span style="font-weight:bold; color:red">Error 404: Function Not Found</span>';
	}
	
	$sql = "SELECT * FROM ".$wpdb->prefix."pureHTML_functions";
	$results = $wpdb->get_results($wpdb->prepare($sql));
	?>
	<script type="text/javascript">
	function confirmMod(id){
		return confirm("Are you sure you want to modify row id: "+id+"?");
	}
	function confirmDel(id){
		return confirm("Are you sure you want to delete row id: "+id+"?");
	}
	</script>
	<h1>Pure HTML</h1>
	<h2>Plugin Options</h2>
	<form action='<?php echo WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/';?>alter.php' method="post">
		<?php wp_nonce_field( plugin_basename(__FILE__), 'PureHTMLNOnce' ); ?>
		<input type="hidden" name='action' value='options' />
		<input type="hidden" name="id" value="0" />
		<p>Current 404 message: <?php echo $fourohfourmsg;?></p>
		<label for="show404">Show the snippet not found message?: </label><input type='checkbox' name='option_show404' value='1' <?php if($show404 == 1)echo "checked='checked'";?> /><br/>
		<label for="fourohfourmsg">Custom 404 message to be displayed: </label>
		<select name='option_404msg'>
			<option value='0'> - Default Message - </option>
			<?php
				$res = "SELECT * FROM ".$wpdb->prefix."pureHTML_functions";
				$res = $wpdb->get_results($res);
				foreach($res as $row){
					echo "<option value='".$row->id."'";
					if($row->id == $fourohfourmsg_id){echo "selected='selected'";}
					echo"> - Snippet ID: ".$row->id." - </option>";
				}
			?>
		</select>
		<br/>
		<input type='submit' class='button-primary' value='Save Plugin Options' />
	</form>
<hr/>
	<h2>Code Snippets</h2>
	<table cellpadding='5' cellspacing='0' width="100%">
	<?php if(sizeof($results) != 0){?>
	<tr><th width='4%' style='border-right:1px #ddd solid;'>ID</th><th width="1%"></th>
	<th width="88%" align="left">Snippet</th>
		<th width="7%" align="right">&nbsp;</th></tr>
	<tr><td style='border-right:1px #ddd solid;' height="10"></td><td colspan='3'></td></tr>
	<?php foreach($results as $row){ ?>
		<tr>
			<th align='center' valign="top" scope="row" style='border-right:1px #ddd solid;'>
				<?php echo $row->id; ?>
			</th>
			<td></td>
			<td align="left">
			<form action='<?php echo WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/';?>alter.php' method="post" onsubmit="return confirmMod(<?php echo $row->id; ?>)">
					<?php wp_nonce_field( plugin_basename(__FILE__), 'PureHTMLNOnce' ); ?>
					<input type='hidden' name='action' value='modify' />
					<input type='hidden' name='id' value='<?php echo $row->id; ?>' />
					<strong>Name:</strong>
					<input type='text' name='name' value='<?php echo $row->name;?>' onblur="javascript:this.style.textDecoration='none'; this.style.cursor='pointer'" onclick="javascript:this.style.textDecoration='underline'; this.style.cursor='text';" title="Click to edit" maxlength="99" style='width:88%; border:0px white solid !important;cursor:pointer; background:none !important;'/>
					<input type='submit' value='Modify' class='button-secondary' style='float:right;' />
					<textarea style="width:99%" rows="2" name='function'><?php echo $row->function; ?></textarea>
				</form>
			</td>
			<td align="left" valign="top">
				<form action='<?php echo WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/';?>alter.php' method="post" onsubmit="return confirmDel(<?php echo $row->id; ?>)">
					<?php wp_nonce_field( plugin_basename(__FILE__), 'PureHTMLNOnce' ); ?>
					<input type='hidden' name='action' value='delete' />
					<input type='hidden' name='id' value='<?php echo $row->id; ?>' />
					&nbsp;|&nbsp;<input type='submit' value='Delete' class='button-secondary' />
				</form>
				
			</td>
		</tr>
	<tr><td style='border-right:1px #ddd solid;' height="20"></td><td colspan='3'></td></tr>
	
	<?php
	}}else{?>
	<tr><td style='border-right:1px #ddd solid;'></td>
	<td colspan='3' align="center"><em>No Snippets Found</em></td></tr>
	<tr><td style='border-right:1px #ddd solid;' height="20"></td><td colspan='3'></td></tr>
	<?php }?>
	<tr><td style='border-right:1px #ddd solid;' height="20"></td><td colspan='3' style='border-top:1px #ddd solid;'></td></tr>
	<tr><th width='4%' style='border-right:1px #ddd solid;'>&nbsp;</th><th width="1%"></th><th width="88%" align="left"><h2>Add A New Snippet</h2></th>
		<th width="7%" align="right">&nbsp;</th></tr>
	<tr><td height="123" style='border-right:1px #ddd solid;'></td><td></td><td>
	<form action='<?php echo WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/';?>alter.php' method="post">
		<?php wp_nonce_field( plugin_basename(__FILE__), 'PureHTMLNOnce' ); ?>
		<input type='hidden' name='action' value='add' />
		<input type='hidden' name='id' value='0' />
		<strong>Name: </strong><input type='text' name='name' id='name' style='width:88%;' maxlength="100" />
		<input type='submit' value='Save Snippet' class='button-primary' />
		<textarea style='width:100%' rows='4' name='function'></textarea><br/>
	</form></td><td></td></tr>
	</table>
	<?php
}
?>
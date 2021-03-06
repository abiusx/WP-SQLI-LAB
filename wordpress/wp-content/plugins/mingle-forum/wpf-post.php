<?php
$q = "";
global $wpdb, $mingleforum;
if($user_ID || $this->allow_unreg()){
if(isset($_GET['quote'])){
	$quote_id = $this->check_parms($_GET['quote']);
	$text = $wpdb->get_row($wpdb->prepare("SELECT text, author_id, `date` FROM $this->t_posts WHERE id = $quote_id"));
	$user = get_userdata($text->author_id);
	$display_name = $this->options['forum_display_name'];
	$q = "[quote][b]".__("Quote from", "mingleforum")." ".$user->$display_name." ".__("on", "mingleforum")." ".$mingleforum->format_date($text->date)."[/b]\n".$text->text."[/quote]";
}
if(($_GET['mingleforumaction'] == "postreply")){
	$options = get_option("mingleforum_options");
	$this->current_view = POSTREPLY;
	$thread = $this->check_parms($_GET['thread']);
	$out = $this->header();
	$out .= "<form action='".WPFURL."wpf-insert.php' name='addform' method='post' enctype='multipart/form-data'>";
	$out .= "<table class='wpf-table' width='100%'>
			<tr>
				<th colspan='2'>".__("Post Reply", "mingleforum")."</th>
			</tr>
			<tr>	
				<td>".__("Subject:", "mingleforum")."</tf>
				<td><input size='50%' type='text' name='add_post_subject' class='wpf-input' value='".__('Re:', 'mingleforum')." ".$this->get_subject($thread)."'/></td>
			</tr>
			<tr>	
				<td valign='top'>".__("Message:", "mingleforum")."</td>
				<td>";
					$out .= $this->form_buttons()."<br/>".$this->form_smilies();
					$out .= "<br /><textarea ".ROW_COL." name='message' class='wpf-textarea' >".stripslashes($q)."</textarea>";
					$out .= "
				</td>
			</tr>";
				$out .= apply_filters('wpwf_form_guestinfo',''); //--weaver--
				$out .= $this->get_captcha();
				if($this->options['forum_allow_image_uploads'])
				{
					$out .= "
					<tr>
						<td valign='top'>".__("Images:", "mingleforum")."</td>
						<td colspan='2'>
							<input type='file' name='mfimage1' id='mfimage' /><br/>
							<input type='file' name='mfimage2' id='mfimage' /><br/>
							<input type='file' name='mfimage3' id='mfimage' /><br/>
						</td>
					</tr>";
				}
				$out .= "
			<tr>
				<td></td>
				<td><input type='submit' id='wpf-post-submit' name='add_post_submit' value='".__("Submit", "mingleforum")."' /></td>
				<input type='hidden' name='add_post_forumid' value='".$this->check_parms($thread)."'/>
				<input type='hidden' name='add_topic_plink' value='".get_permalink($this->page_id)."'/>
			</tr>
			</table></form>";
		$this->o .= $out;
	}

if(($_GET['mingleforumaction'] == "editpost")){
	$this->current_view = EDITPOST;
	if(is_numeric($_GET['id'])) //is_numeric prevents SQL injections here
		$id = $_GET['id'];
	else
		$id = 0;
	$thread = $this->check_parms($_GET['t']);
	$out = $this->header();
	$post = $wpdb->get_row($wpdb->prepare("SELECT * FROM $mingleforum->t_posts WHERE id = $id"));
	if(($user_ID == $post->author_id && $user_ID) || $mingleforum->is_moderator($user_ID, $mingleforum->forum_get_forum_from_post($thread))) //Make sure only admins/mods/post authors can edit posts
	{
		$out .= "<form action='".WPFURL."wpf-insert.php' name='addform' method='post'>";
		$out .= "<table class='wpf-table' width='100%'>
				<tr>
					<th colspan='2'>".__("Edit Post", "mingleforum")."</th>
				</tr>
				<tr>	
					<td>".__("Subject:", "mingleforum")."</tf>
					<td><input size='50%' type='text' name='edit_post_subject' class='wpf-input' value='".stripslashes($post->subject)."'/></td>
				</tr>
				<tr>	
					<td valign='top'>".__("Message:", "mingleforum")."</td>
					<td>";
							$out .= $mingleforum->form_buttons()."<br/>".$mingleforum->form_smilies();

						$out .= "<br /><textarea ".ROW_COL." name='message' class='wpf-textarea' >".stripslashes($post->text)."</textarea>";
					$out .= "</td>
				</tr>
				<tr>
					<td></td>
					<td><input type='submit' id='wpf-post-submit' name='edit_post_submit' value='".__("Save Post", "mingleforum")."' /></td>
					<input type='hidden' name='edit_post_id' value='".$post->id."'/>
					<input type='hidden' name='thread_id' value='".$thread."'/>
					<input type='hidden' name='add_topic_plink' value='".get_permalink($this->page_id)."'/>
				</tr>
				</table></form>";
			$this->o .= $out;
	}
	else
		wp_die("Haha, nice try!");
}
}
else
	wp_die("Thanks, but no thanks");
?>
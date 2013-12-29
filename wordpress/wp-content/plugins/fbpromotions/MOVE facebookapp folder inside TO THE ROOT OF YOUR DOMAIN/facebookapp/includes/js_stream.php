<?php
# includes the stream publish functions
?>

<script type="text/javascript">

function streamPublish(name, description, hrefTitle, hrefLink, userPrompt){
	FB.ui(
    {
        method: 'stream.publish',
        message: '',
        attachment: {
            name: name,
            caption: '',
            description: (description),
            href: hrefLink,
			media: [{
				type: 'image',
				src: '<?php if(isset($_REQUEST['browse_id'])){show_entry_image();}else{show_stream_image();}?>',
				href: hrefLink
			}]
		},
	    action_links: [{
			text: 'Enter', href: '<?php echo $fbmo->options['fb_canvas_url'].$promo_row["promo_page"].".php?promo_id=".$promo_row['promo_id']."&ref_id=$fbmo->fbuser"."&ref_type=ref";?>' }
		],
        user_message_prompt: userPrompt
    },
	function(response) {
     if (response && response.post_id) {
       // call an AJAX routine to post this.
		record_post("share")
     }
   }
	);
}
function publishShare()	{
	streamPublish(<?php show_share_function()?>);
	publishShareClose('share_window');
}
function publishEntry()	{
	streamPublish(<?php show_share_new_function()?>);
	publishShareClose('entry_window');
}
function publishVote()	{
	streamPublish(<?php show_share_function()?>);
	publishShareClose('vote_window');
}

function publishShareClose(closeid)	{
	var share_form=document.getElementById(closeid);
	share_form.style.display='none';
}
function publishShareOpen(openid)	{
	var share_form=document.getElementById(openid);
	share_form.style.display='block';
}
function record_post(type){
    $.ajax({
        type: "POST",
        url: "<?php echo(get_option('siteurl')."/facebookapp/asynch_handler.php")?>",
        data: "uid=<?php echo($fbmo->fbuser)?>&promo_id=<?php echo($get_promo_id)?>&type="+ type,
        error: function(msg){
         //  alert(msg);
        }
    });
}
</script>

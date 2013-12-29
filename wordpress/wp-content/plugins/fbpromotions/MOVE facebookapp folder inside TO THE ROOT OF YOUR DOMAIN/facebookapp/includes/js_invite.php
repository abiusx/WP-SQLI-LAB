<?php
# invite functions
?>
 <fb:serverFbml width="650px">
Some text here.<br />
   <script type="text/fbml">
     <fb:fbml>
        <fb:request-form
             action='<?php echo(show_promo_url())?>'
             method='POST'
			invite='true'
             type='<?php echo($promo_row["promo_name"])?>'
             content='Check out this Great Promotion'
             <fb:req-choice url='<?php echo(show_promo_url())?>' label='Enter'/>"
             <fb:multi-friend-selector
				actiontext='Invite your friends to Participate!'
				cols='4' email_invite='false'>
             </fb:multi-friend-selector>
        </fb:request-form>
      </fb:fbml>
 </script>
 </fb:serverFbml>

<script>
function publishInvite()	{
	streamPublish(<?php show_share_function()?>);
	publishShareClose("invite_window");
}
function publishInviteClose()	{
	var share_form=document.getElementById("invite_window");
	share_form.style.display='none';
}
function publishInvite_window()	{
	var share_form=document.getElementById("invite_window");
	share_form.style.visibility='block';
}
</script>
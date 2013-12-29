<?php

function widget_odihost_newsletter($args) {
	extract($args);
	echo $before_widget;
	echo $before_title;
	
	$options = get_option('widget_odihost_newsletter');
	echo $options['title'];
	echo $after_title;

	?>
<!--	<script type="text/javascript" src="<?php echo get_option("siteurl"); ?>/wp-content/plugins/odihost-newsletter-plugin/js/jquery.js"></script>-->
	<script>
		function ajax_post(post_vars, container){
		var form=jQuery(document.forms["form_subscribe"]);
			jQuery.ajax({
				url: form.attr('action'),
				type: form.attr('method'),
				data: post_vars,  		
				cache: false,
				success: function(html){
				jQuery(container).html(html);
					alert(html);
					jQuery("#newsletter_name").val();
					jQuery("#newsletter_email").val();
		  }
		});	
	}
	
	function subscribe()
	{
		ajax_post(jQuery(document.forms["form_subscribe"]).serialize(), "#register_summary");
	}
	</script>
	<form action="<?php echo get_option("siteurl")."/wp-content/plugins/odihost-newsletter-plugin/includes/save-user.php";?>" method="post" id="form_subscribe" name="form_subscribe">
		<div><?php echo stripslashes(get_option('newsletter_form_header'));?></div>
		<div>Name:&nbsp;<input type="text" name="newsletter_name" id="newsletter_name"/></div>
		<div>Email:&nbsp;<input type="text" name="newsletter_email" id="newsletter_email"/></div>	
		<div><input type="button" value="Subscribe" onclick="subscribe();"/></div>
		</form>
	<?php
	echo $after_widget;
}
 
	function odihost_widget_control()
	{
	
		if ($_POST['odihost-title']) {
			$options['title'] = strip_tags(stripslashes($_POST['odihost-title']));
		}
	
		update_option('widget_odihost_newsletter', $options);
		$options = get_option('widget_odihost_newsletter');
	
		?>
		<p><label for="odihost-title">Title: <input type="text" style="width: 250px;" id="odihost-title" name="odihost-title" value="<?php echo htmlspecialchars($options['title']); ?>" /></label></p>
	<?php
	}
		
	function odihost_newsletter_init()
	{
		wp_register_sidebar_widget("widget_odihost_newsletter","Odihost Newsletter Opt-in Form" ,'widget_odihost_newsletter');
        register_widget_control('widget_odihost_newsletter', 'odihost_widget_control');
	}
	add_action("plugins_loaded", "odihost_newsletter_init");

?>
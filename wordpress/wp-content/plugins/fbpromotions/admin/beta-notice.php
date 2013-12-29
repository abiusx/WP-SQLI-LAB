<?php
/**
*	BugsGoViral Plugin for Worpress
*	Beta Notice
*
*	@filesource 			beta_notice.php
*	@description			Default dashboard view for Bugs Go Viral Plugin
* */
include("version.php");

/**
* Start display code
*/
?>
<div id="admin_dashboard_container" class="wrap">
		<?php
      if(!get_option("key")){
	   ?>
		<div class='update-nag'>Account setup is mandatory before configuring any promo.</div>
      <?php
	  }elseif(empty($fbmo->options["fb_application_secret"])){
        ?>
        <div class='update-nag'>Facebook Settings needs to be updated.</div>
        <?php
        }
        ?>
	<?php do_html_admin_masthead("admin_dastboard","masthead","Beta Notice","Admin Heading",0,'');
	 ?>

    
    <p>Please note that this release of the Bugs Go Viral Wordpress plugin is a BETA VERSION. We appreciate any and all feedback on your experience with this plugin. Please feel free to use the following links on our support web site to work through any questions or issues:
    </p>
    <p>
    <ul style="list-style-type:circle; list-style-position:inside">
    <li><a href="http://bugsgoviral.com/support/bug-report/" target="_blank">Bug Report</a></li>
    <li><a href="http://bugsgoviral.com/support/facebook-guidelines/" target="_blank">Facebook Guidelines</a></li>
    <li><a href="http://bugsgoviral.com/support/forum/" target="_blank">Support Forum</a></li>
    <li><a href="http://bugsgoviral.com/changelog/" target="_blank">Changelog</a></li>
    <li><a href="http://bugsgoviral.com/how-to/" target="_blank">How-To's</a></li>
</ul></p>
<p>In addition, we are announcing a 10% discount on all purchases made during this beta launch.</p>
	<?php do_html_admin_dashboard_main("admin_dashboard","content","","",$val=1) ?>

</div><!-- DIV END "admin_dashboard_container" -->

<?php do_html_admin_footer("admin_dashboard","admin_footer") ?>



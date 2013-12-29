<?php
function create_admin_menu()
{
	if (function_exists('add_menu_page')) {
		add_menu_page('Odi Newsletter', 'Odi Newsletter', 1, 'odihost_newsletter', 'odihost_newsletter');		}

		  
	if (function_exists('add_submenu_page')) {	
		//add setting menu for admin
		if (function_exists('add_submenu_page')) {
			add_submenu_page('odihost_newsletter', 'Newsletter', 'Newsletter', 1, 'odihost_newsletter', 'odihost_newsletter');
			add_submenu_page('odihost_newsletter', 'Subscriber', 'Subscriber', 1, 'odihost_newsletter_subscriber', 'odihost_newsletter_subscriber');
			add_submenu_page('odihost_newsletter', 'Setting', 'Setting', 1, 'odihost_newsletter_setting', 'odihost_newsletter_setting');
			add_submenu_page('odihost_newsletter', 'Export Import', 'Export Import', 1, 'odihost_newsletter_export_import', 'odihost_newsletter_export_import');
			add_submenu_page('odihost_newsletter', 'Email Schedule', 'Email Schedule', 1, 'odihost_newsletter_email_schedule', 'odihost_newsletter_email_schedule');
		}
	}
}

function odihost_newsletter_email_schedule()
{
	include("email_schedule.php");
}

function odihost_newsletter_export_import()
{
	include("exim.php");
}
function odihost_newsletter_subscriber()
{
	include('subscriber.php');
}

function odihost_newsletter()
{
	include('newsletter.php');
}

function odihost_newsletter_setting()
{
	include('setting.php');
}


function odihost_newsletter_install() {
	global $wpdb;
	
	$table_users = $wpdb->prefix . "newsletter_users";
	$table_newsletter = $wpdb->prefix . "odi_newsletter";
	$table_email_schedule = $wpdb->prefix . "newsletter_email_schedule";

		// Table does not exist; create new
	$sql = "create table if not exists `" . $table_users . "` (id int(10) unsigned NOT NULL auto_increment, joindate datetime,
ip varchar(50),
name varchar(500),
email varchar(100),
status int,
 PRIMARY KEY(`id`), KEY `accountid` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
";
	$result = $wpdb->query($sql);

	// Table does not exist; create new
	$sql = "create table if not exists `" . $table_newsletter . "` (id int(10) unsigned NOT NULL auto_increment, created datetime,
subject varchar(500),
message text,
status int,
 PRIMARY KEY(`id`), KEY `odinewsletterid` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
";
	$result = $wpdb->query($sql);
	
	$sql = "create table if not exists `odinewslettercron` (id int(10) unsigned NOT NULL auto_increment, newslettercronnewsletterid int,
newslettercronsubscriberid text,
 PRIMARY KEY(`id`), KEY `newslettercronid` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
";
	$result = $wpdb->query($sql);

 
	$sql = "create table if not exists `" . $table_email_schedule . "` (id int(10) unsigned NOT NULL auto_increment, subject varchar(100),
message text,
daysent int,
status int,
 PRIMARY KEY(`id`), KEY `accountid` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;
";
	$result = $wpdb->query($sql);
	
	$sql = "create table if not exists `newsletterstat` (id int(10) unsigned NOT NULL auto_increment, newsletterstatnewsletterid int,
newsletterstatsent int,
newsletterstatopened text,
newsletterstatsenddate date,
 PRIMARY KEY(`id`), KEY `newsletterstatid` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
 $result = $wpdb->query($sql);
 
	$sql = "create table if not exists `newsletterclickstat` (id int(10) unsigned NOT NULL auto_increment, newsletterclickstatnewsletterid int,
newsletterclickstatsubscriberid int,
newsletterclickstaturl varchar(100),
newsletterclickstatdate date,
 PRIMARY KEY(`id`), KEY `newsletterclickstatid` (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
	$result = $wpdb->query($sql);

	$insert = "INSERT INTO `$table_users` (`joindate`, `ip`, `email`, `name`,`status`) " .
		"VALUES (now(),'" . odihost_getip() .
		"','" . get_option('admin_email') . "','admin',1)";
	$result = $wpdb->query($insert);

	// default values
	$blogname = get_option('blogname');
	add_option('newsletter_email_from', get_option('admin_email') );
	add_option('newsletter_email_test', get_option('admin_email') );
	add_option('newsletter_email_rate', 250 );
	
	add_option('newsletter_email_subject', "$blogname - Newsletter subscription");
	add_option('newsletter_email_message', "Thank you for subscribing at $blogname.\n
You can verify your email at *link*.\n\n

www.odihost.com");
	
	add_option('newsletter_email_subject_subscriber', "$blogname - Your subscription");
	add_option('newsletter_email_message_subscriber', "Thanks you. Now you are subsribed at $blogname.\n");

	add_option('newsletter_msg_dup', "Yout e-mail is already subscribed.");
	add_option('newsletter_msg_fail', "Failed sending to e-mail address.");
	add_option('newsletter_msg_sent', "Thank you for subscribing. Please check your email to verify. Please check your spam folder too.");

	add_option('newsletter_form_header', "Opt-in form header");
	add_option('newsletter_form_footer', "Opt-in form footer");
	add_option('newsletter_form_email', "E-mail:");
	add_option('newsletter_form_send', "Join");
}
?>
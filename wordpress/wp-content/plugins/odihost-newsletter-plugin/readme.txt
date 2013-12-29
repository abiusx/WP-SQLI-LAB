=== OdiHost Newsletter Plugin ===
Contributors: OdiHost - Ian
Tags: Newsletter, odihost, newsletter plugin, opt-in, email marketing, email, send email , send email plugin, mailing list
Requires at least: 3.0.0
Tested up to: 3.0.4

This plugin is a newsletter plugin from OdiHost. It can show opt-in form and save user data. You can then send email from your user list.
== Description ==

This plugin is a newsletter plugin from OdiHost. It can show opt-in form and save user data. You can then send email from your user list, import and export data. There is also email schedule function, where you can set it to send email after a certain day they subscribe. You can read more detail and comment <a href="http://odihost.com/newsletter-plugin" target="_blank" title="newsletter plugin">here</a>.
<br><br>
Features:
<ul>
<li>Opt-in form widget, so you can place the opt-in / subscribe form in your sidebar easily.
<li>See your subscriber data and their status.
<li>Send email to your subscriber.
<li>Import and export subscriber data.
<li>Set schedule email, where you can set it to send email after a specific day user subscribe. 
<li>Stats for your newsletter. You can check how many people open your email and which link they clicked.
</ul>
== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add opt-in form using widget. 
4. Make wp-content/plugins/odihost-newsletter/uploads permission to 777 for import function
5. Set cron dailly for wp-content/plugins/odihost-newsletter/includes/cronschedule.php which will send your user email based on their subscribe date.
6. Set cron hourly for wp-content/plugins/odihost-newsletter/includes/cronbatch.php which will send your newsletter in batch.
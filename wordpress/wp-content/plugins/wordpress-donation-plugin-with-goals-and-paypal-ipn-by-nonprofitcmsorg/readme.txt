=== Plugin Name ===
Contributors: vofficeware
Donate link: http://www.nonprofitcms.org/2010/12/wordpress-donate-plugin/
Tags: donations, donate, paypal, ipn, goals, meter
Requires at least: 2.7
Tested up to: 3.0.4
Stable tag: trunk

Donations with the ability to specify an unlimited number of campaigns / goals.  Integrates with PayPal IPN to display a progress meter / donor list.

== Description ==

The NonprofitCMS.org WordPress Donate Plugin allows you to create unlimited donation campaigns with goals, capture donor information and gives a donation progress for each campaign!  

The plugin integrates with PayPal IPN and updates a progress meter in real time.  Donors that choose not to be anonymous can be seen in a table.

Donors receive a customizable confirmation email.  You can view specific donor information in the WordPress backend.

Visit plugin website <http://www.nonprofitcms.org/2010/12/wordpress-donate-plugin/> for premium version provides ability to export to CSV.



== Installation ==
Download: [WordPress Donation Plugin](http://www.nonprofitcms.org/2010/12/wordpress-donate-plugin/ "WordPress Donation Plugin")

1. Install the plugin via WordPress.org or unzip the contents of the file into your wp-content/plugins folder.

2. Activate the plugin

3. Setup your PayPal and Email Template settings.

4. Setup one or more campaigns.


== Frequently Asked Questions ==

*Short Codes*
After creating a campaign, you are presented with a campaign ID.  Use that as the cid parameter.

\[wpdonatebuy cid=15\] (generates a donation form based on the chosen fields of the campaign ready to submit to PayPal)

\[wpdonatemeter cid=15\] (Generates a progress meter showing amount donated and amount left)

\[wpdonategoal cid=15\] (generates a string of the total goal amount, no currency symbol is used)

\[wpdonatecollected cid=1\] (generates a string of the total amount collected, no currency symbol is used)

\[wpdonatorlist cid=15\] (generates a table of non-anonymous donors for this campagin)


== Screenshots ==

1. Setup your PayPal and Email Confirmation Setings
2. Create a Campaign and Choose Your Goal / Details / Fields of Information to Collect
3. See a List of campaigns and click through to see donators
4. Put it all together with a few short codes

== Changelog ==

= 1.0 =
* First stable version



== Upgrade Notice ==

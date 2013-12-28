=== Paid Downloads ===
Contributors: ichurakov
Plugin Name: Paid Downloads
Plugin URI: http://www.icprojects.net/paid-downloads-plugin.html
Author: ichurakov
Author URI: http://www.icprojects.net/
Donate link: http://www.icprojects.net/paid-downloads-plugin.html
Tags: download, paypal, robokassa, digital shop
Requires at least: 3.0
Tested up to: 3.1.3
Stable tag: 2.01

The plugin allows to sell digital content using PayPal and Robokassa. It delivers temporary download link to your customer after completed payment.

== Description ==

Paid downloads plugin easily allows you to sell any digital content. The plugin automatically delivers the product (temporary encrypted download link) to you customer after completed payment done via PayPal or Robokassa. The only actions you have to do are uploading files and inserting short code like [paid-downloads id="XXX"] into your posts or pages. The list of supported currencies: USD, AUD, EUR, GBP, NZD, RUR.

Using the plugin

1. Upload folder "paid-downloads" (and its content) into your Wordpress plugin folder (normally it is /wp-content/plugins/).
2. Go to Wordpress admin area and activate the plugin in plugin sections (like you do for any other plugins). Once activated, it will create a menu "Paid Downloads" in left side column in the admin area. This menu contains two items: Settings and Files.
3. Click left side menu "Paid Downloads >>> Settings" and do required settings. Set your PayPal ID, Robokassa Merchant parameters, e-mail address for notifications, e-mail templates for success and failed payments, download link lifetime, etc. You also can customize "Buy Now" button.
4. Click left side menu "Paid Downloads >>> Files" and upload the files you would like to sell. In this section you also can set the price for your files, see all payment transactions, generate and see temporary download links.
5. Once file uploaded look at column "Short Code". This is short code which you can insert into your posts or pages. The short code is like that: [paid-downloads id="XXX"] (XXX - is an ID of file).
6. Go to any post/page edit page and insert short code there. This short code will be replaced by "Buy Now" button automatically (or by download link if the price is 0.00).

There are two kinds of workflows at front-end

1. You accept PayPal payments only. If user decides to purchase your digital product, he/she can click "Buy now" button. After that the user will be redirected to PayPal website to do the payment. After payment was done (completed and cleared), the user receives download link which is valid 2 days (period of validity is defined by administrator). Download link is sent to user's PayPal e-mail.
2. You accept Robokassa payments with/without PayPal payments. If user decides to purchase your digital product, he/she can click "Buy now" button. The popup box appears. It offers available payment methods. If user decides to pay via PayPal he/she can click PayPal button and complete the payment. If user decides to pay via Robokassa, he/she must enter e-mail address (this e-mail address is used to deliver download link) and click Robokassa button. After payment was done (completed and cleared), the user receives download link which is valid 2 days (period of validity is defined by administrator).

Plugin requires PHP version 5 or higher installed on your server.

For more details please visit <a href="http://www.icprojects.net/paid-downloads-plugin.html">Paid Downloads plugin page</a>

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload folder "paid-downloads" (and its content) into your WordPress plugin folder (normally it is /wp-content/plugins/).
2. Go to WordPress admin area and activate the plugin in plugin sections (like you do for any other plugins).

== Frequently Asked Questions ==

None.

== Screenshots ==

None.

== Changelog ==

= 2.01 =
* Currency list updated. Now you can use the following currencies: USD, AUD, EUR, GBP, NZD, RUR.
* Minor bugs fixed.

= 2.0 =
* Robokassa payment method added.
* Currency list updated. Now you can use the following currencies: USD, EUR, RUR.
* Minor bugs fixed.

= 1.28 =
This is the first version of Paid Downloads plugin.

== Upgrade Notice ==

= 2.01 =
Deactivate plugin. Upload new plugin files. Activate plugin.

= 2.0 =
Deactivate plugin. Upload new plugin files. Activate plugin.

= 1.28 =
This is the first version of Paid Downloads plugin.

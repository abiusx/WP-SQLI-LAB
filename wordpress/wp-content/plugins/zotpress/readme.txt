=== Plugin Name ===
Contributors: kseaborn
Plugin Name: Zotpress
Plugin URI: http://katieseaborn.com/plugins/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5HQ8FXAXS9MUQ
Tags: zotero, zotpress, citation manager, citations, citation, cite, citing, bibliography, bibliographies, reference, referencing, references, reference list, reference manager, academic, academia, scholar, scholarly, cv, curriculum vitae, resume
Author URI: http://katieseaborn.com/
Author: Katie Seaborn
Requires at least: 3.0.4
Tested up to: 3.2.1
Stable tag: 4.4

Zotpress displays your Zotero citations on Wordpress.

== Description ==

[Zotpress](http://katieseaborn.com/plugins/ "Zotpress for WordPress") brings scholarly blogging to WordPress. This plugin displays your [Zotero](http://zotero.org/ "Zotero") citations on your Wordpress blog. [Zotero](http://zotero.org/ "Zotero") is a community-based cross-platform citation manager that integrates with your browser and word processor.

= Features =
* Display your Zotero citations on your blog using in-text citations and bibliographies
* Display citations, collections, or tags
* Selective CSS styling via IDs and classes
* Add both user and group Zotero accounts
* Add thumbnail images to your citations
* Let visitors download your publications
* And more!

Tested in Firefox 5, Safari 5, Chrome 12, IE7 and IE8.

= Requirements =
jQuery included in your theme, and cUrl [preferably] or file_get_contents enabled on your server.  Optional, but recommended: OAuth enabled on your server.

== Installation ==

1. Upload the folder `zotpress` to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. If your theme doesn't already support jQuery, you'll need to add `<?php wp_enqueue_script("jquery"); ?>` above the `<?php wp_head(); ?>` call in your theme's header template.
1. Place the `[zotpress]` shortcode in your blog entry or enable the Zotpress sidebar widget.

= Shortcode =
You can display your Zotero citations in a number of ways. To display a complete list of citations for an account in the default bibliography style (APA), simply use this shortcode:

[zotpress userid="00000"]

An example of the shortcode using parameters is:

[zotpress collection="ZKDTKM3X" limit="5"]

This shortcode will display a list of five citations from the collection with the key "ZKDTKM3X".

You can also use in-text citations:

[zotpressInText item="U9Z5JTKC" pages="36-45"]

This shortcode will display the following APA-styled in-text citation for the citation with the key "U9Z5JTKC": (Seaborn, 2011, p. 36). The full citation will be shown in an auto-generated bibliography placed below the post.

Check out the "Help" page on your installation of Zotpress for more information and a full listing of parameters for both shortcodes.

== Frequently Asked Questions ==

The F.A.Q. can be found on the "Help" page of every Zotpress install. If you have a question that isn't answered there, freel free to post a message in the [forums](http://wordpress.org/tags/zotpress "Zotero forums on Wordpress.com").

== Screenshots ==

1. Display and filter your Zotero citations by account, collection or tag on the admin page. Upload images to citations. Special characters are supported.
2. Manage both user and group Zotero accounts. Easy private key creation using OAuth (as long as your server supports it).
3. Search for item keys, citation ids and tag names using the convenient "Zotpress Reference" meta box.

== Changelog ==

= 4.4 =
* A number of security measures added.
* Fixed "Help" page shortcode for in-text citations and private vs. public groups: oops!
* The Zotpress shortcode now accepts lists for these parameters: collection, item.
* Notes can now be shown, if made publicly available through Zotero.
* Zotpress Reference should now show up on custom post type writing/editing pages.
* Zotpress Reference now working with the latest versions of Chrome and Safari.

= 4.3 =
* Introducing "Zotpress InText", a new shortcode that let's you add in-text citations, and then auto-generates a bibliography for you. jQuery must be enabled. Only supports APA style; requests can be made in the forums. Use information can be found in your Zotpress installation's "Help" page.
* Recaching and auto-checking for new or updated Zotero data back in action.
* The "collection" shortcode parameter now working.
* Zotero XML data gathering functions optimized.
* Tags with spaces are now working again for the "tag" shortcode parameter.
* Tag shortcode parameter now accepts nonexistent tags.

= 4.2.7 =
* Error display error fixed.

= 4.2.6 =
* Fixed bullet image issue.

= 4.2.5 =
* Fixed sidebar issue: having an author is no longer required.

= 4.2.4 =
* Fixed sidebar widget error and display issue.
* Added more information to and sorting of citations listed in the Zotpress Reference widget.

= 4.2.3 =
* More friendly XML error messages, including ability to (at least try) repeating the Zotero request.
* Spite-ified most images for quicker display.
* Citation images can now be deleted.

= 4.2.2 =
* Bugfix: Typo!

= 4.2.1 =
* Bugfix: Limit issue resolved.

= 4.2 =
* Bugfix: Styles now working again.
* Bugfix: Now only grabbing top level items.
* Bugfix: Sidebar widget working again.
* Metabox widget refined: Limit removed, account info integrated, and tags and collections alphabetized.

= 4.1.1 =
* Bugfix: Can now sort by ASC or DESC order.

= 4.1 =
* Bugfixes: Filtering by author and date reinstated.
* New: Titles by year. (New parameter: title)

= 4.0 =
* Switched method of requesting from jQuery to PHP. Should mean a speed increase (particularly for Firefox users).
* Many shortcode parameters have been changed; these parameters are now deprecated: api_user_id (now userid), item_key (now item), tag_name (now tag), data_type (now datatype), collection_id (now collection), download (now downloadable), image (now showimage).
* New shortcode parameter "sortby" allows you to sort by "author" (first author) and "date" (publication date). By default, citations are sorted by latest added.

= 3.1.3 =
* Temporary fix for web servers that don't support long URLs. Unfortunately no special caching for these folks. New solution in the works.

= 3.1.2 =
* Added backwards compatibility measure with respect to the new api_user_id / nickname requirement.
* Fixed citation display positioning bugs.
* Applied new caching method to sidebar widget.

= 3.1.1 =
* Fix: Sidebar widget bug.

= 3.1 =
* New way of caching requests. Speed increase for requests that have already been cached.
* No more multiple accounts per shortcode. A "user_api_id" or "nickname" must be set.
* No more collection titles. You can use the Zotero Reference meta box to find and add this information above collection shortcode calls.

= 3.0.4 =
* Fixed display images issue.
* Separated out sidebar widget code from main file.

= 3.0.3 =
* Groups accounts citation display fixed.

= 3.0.2 =
* Meta box fixed in IE and Safari.
* Styles fixed in IE and Safari.

= 3.0.1 =
* Sidebar widget fixed.
* Styles in IE refined.
* Conditional OAuth messages implemented.

= 3.0 =
* New "Zotpress Reference" widget, meant to speed up the process of adding shortcodes to your posts and pages by allowing you to selectively search for ids directly on the add and edit pages.
* OAuth is now supported, which means that you don't have to go out of your way to generate the required private key for your Zotero account anymore (unless your server doesn't support OAuth, of course).
* I've changed the way Zotpress's admin splash page loads. Before, the page would hang until finished loading the latest citations from Zotero. This is a friendlier way of letting you know what Zotpress is up to.
* Manual re-caching and clear cache options added, for those who desire to refresh the cache at their leisure.
* Citations that have URLs will now have their URLs automatically hyperlinked.
* More IDs and classes added for greater CSS styling possibilities.
* Improved handling of multiple Zotpress shortcode calls on a single page.
* Code reduced and refined plugin-wide, which should equal an overall performance improvement.
* "Order" parameter no longer available, at least for now; see http://www.zotero.org/support/dev/server_api
* "Forcing cURL" option abandoned. If your server supports it, cURL will be used; otherwise, Zotpress will resort to file_get_contents(). 

= 2.6.1 =
* Can now give group accounts a public key.
* Downloads can now be accessed by anyone (assuming you've enabled downloading).

= 2.6 =
* Important: Reduced multiple instantiations of JavaScript.
* Download option added to Widget.
* Proper download links for PDFs implemented.

= 2.5.2 =
* Fixed image display for author/year citations.

= 2.5.1 =
* Fixed single citation display bug.

= 2.5 =
* Re-wrote display code.
* Tidied up JavaScript.
* Fixed update table code.

= 2.4 =
* Can now display by year.
* New option to display download links, should they be available.

= 2.3 =
* Fixed Group "invalid key" error.

= 2.2 =
* Fixed CURLOPT_FOLLOWLOCATION error.

= 2.1 =
* Now cURL-friendly again.

= 2.0 =
* Zotpress completely restructured.
* Most requests now made through PHP. Shortcode requests made through PHP/jQuery combo for user-friendliness on the front-end.
* Cross-user caching implemented. Updates request data every 10 minutes and only if request made.
* Increased security now that private keys are no longer exposed through JavaScript.
* Can now filter by Tag in admin.

= 1.6 =
* Critical request method issue fixed.

= 1.5 =
* Groups citation style issue fixed.

= 1.4 =
* Caching enabled, which should speed things up a bit.

= 1.3 =
* Added cURL, which is (maybe?) quicker, (definitely?) safer, and (more likely to be?) supported. Requests default to cURL first now.

= 1.2 =
* Optimized JavaScript functions. Fixed some grammatical errors on the Help page. More selective loading of JavaScript. And most importantly ... added a Zotpress widget option. This also means you can have more than one Zotpress call on a single page.

= 1.1 =
* Fixed up the readme.txt. Added a friendly redirect for new users. Made IE8-compliant. Moved some JS calls to footer. Now selectively loads some JS. Made tags and collections into lists for easier formatting.

= 1.0 =
* Zotpress makes its debut.

== Upgrade Notice ==

= 1.2 =
Lots of little issues fixed. Plus, you can now use a Zotpress widget instead of shortcode.

= 1.3 =
Implemented cURL, which should help those having read/write issues on their server.

= 1.4 =
Speed increase with newly added caching feature.

= 1.5 =
Important: Groups citation style issue fixed.

= 1.6 =
Critical request method issue fixed.

= 2.0 =
Zotpress overhaul. Security and performance increases.

= 2.1 =
Now cURL-friendly again.

= 2.2 =
Fixed CURLOPT_FOLLOWLOCATION error.

= 2.3 =
Fixed Group "invalid key" error.

= 2.4 =
Can now display by year. Option to display download links.

= 2.5 =
Re-wrote display code and tidied up JavaScript. Fixed update table code.

= 2.5.1 =
Fixed single citation display bug.

= 2.6 =
Important: JavaScript reductions; download option added to Widget; proper PDF download links.

= 2.6.1 =
Downloads can now be accessed by anyone.

= 3.0 =
Major release! OAuth, convenient "Zotpress Reference" meta box, friendly lag handling, numerous bug fixes, and more!

= 3.0.1 =
Sidebar widget fixed.

= 3.0.2 =
Meta box now working in IE and Safari!

= 3.0.3 =
Groups accounts citation display fixed.

= 3.0.4 =
Fixed display images issue.

= 3.1 =
Speed increase and a new way of caching. No more multiple accounts per shortcode. No more auto-display of collection title.

= 3.1.1 =
Fixed Sidebar widget bug.

= 3.1.2 =
Bug fixes and clean up.

= 3.1.3 =
Bug fixes.

= 4.0 =
Requests now processed by PHP instead of jQuery. Shortcode parameters re-envisioned (but backwards-compatible). Can now sort by author and date.

= 4.1 =
Bugfixes: Filtering by year and author reinstated. New: Titles for year.

= 4.1.1 =
Bugfix: Can now sort by ASC or DESC order.

= 4.2 =
Bugfixes and metabox widget refinements.

= 4.2.1 =
Bugfix: Limit issue resolved.

= 4.2.2 =
Bugfix: Typo!

= 4.2.3 =
Friendly XML error messages, spite-ified images, and ability to delete citation images.

= 4.2.4 =
Fixed sidebar widget. Refined Zotpress Reference widget.

= 4.2.5 =
Fixed sidebar widget issue.

= 4.2.6 =
Fixed bullet image issue.

= 4.2.7 =
Error display error fixed.

= 4.3 =
Zotpress InText and various fixes.

= 4.4 =
Security measures added. Fixed "Help" page info. Zotpress shortcode now accepts lists for these parameters: collection, item. Notes can now be shown. Zotpress Reference on custom post type writing/editing pages and working with the latest versions of Chrome and Safari.
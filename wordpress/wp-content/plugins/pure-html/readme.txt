=== PureHTML ===
Contributors: Hit Reach
Donate link: 
Tags: html, iframe, embed, form action, code, markup, mark-up
Requires at least: 2.5
Tested up to: 3.1
Stable tag: 1.0.0

Pure HTML allows you to add html markup that is normally removed by visual mode in the post editor.

== Description ==

Pure HTML is a new plug-in for Wordpress that allows you to add HTML markup that is normally removed by visual mode in the post editor.  It comes with the ability to save code snippets for use on multiple pages without needing to update all pages when making a change to that particular snippet

== Usage ==

Using Pure HTML is simple; either place your HTML inside the Pure HTML shortcode [pureHTML] or save your HTML as a snippet and call it using the ID attribute inside the shortcode tag, e.g. [ pureHTML id=1 ]

When inserting your HTML directly into the page using the Pure HTML shortcode you will need to change ALL &lt; and &gt;'s to [ and ] respectively. These prevent Wordpress removing them when the view is changed to visual mode. These square brackets are then changed back at run time.

At run time ALL square brackets are converted to their &lt; &gt; counter parts so to add in a square bracket that isn't to be converted you need to 'escape' it using a \ so it will be \].

= Saved HTML Snippets =

Using saved HTML snippets is simple, on the Pure HTML Snippets page you can see a list of the snippets you have saved.  On this screen you can add, modify and remove snippets.

There is no limit to the number of snippets you can save except for the maximum size of your database!  To add a new snippet simply enter a name for it in the name field, this name is used to find and identify it easily, this could be a description of the snippet or just a name, however there is a character limit of 100 characters.  Next, add your HTML to the text box, this HTML does NOT use the square bracket replacement system that the inline HTML does.  Once saved the snippet will be shown at the top of the page with an ID. It is this ID that you place into the Pure HTML shortcode to use that snippet.  For example: [ pureHTML id=1 ] will use the snippet ID 1.

= Custom 404 Messages =

To set a custom 404 message, just save your 404 message as a snippet then select that snippet to be the 404 message.  Alternatively you can hide the 404 message completely in the option screen.

== Installation ==

Installation is Quick, just upload the 2 PHP files to a Pure HTML folder within wp-content/plugins and then activate the plugin through the admin dashboard, or find the Pure HTML plugin using the Wordpress plugin search and click install

== Frequently Asked Questions ==

= Q. What Tags Are Automatically Removed? =
Currently all &lt;br /&gt; and &lt;p&gt; (and its closing counterpart) tags are removed from the input code because these are the tags that Wordpress automatically add.

= Q. How Do I Add Tags Without Them Being Stripped? =
If you want to echo a paragraph tag or a line break, or any other tag (strong, em etc) instead of enclosing them in &lt; and &gt; tags, enclose them in [ ] brackets for example [p] instead of &lt;p&gt; The square brackets are converted after the inital tags are stripped and function as normal tags.

= Q. How Do I Include a [ or ] In My Output Without It Being Removed =
To prevent [ or ] being changed to the &lt; or &gt; (respectively) you will need to `escape` it using a \, so ] will become \] and [ will become \[.

= Q. What Happens To The Output If I Delete The Snippet It Uses? =
If you delete a snippet that is being used in a page then the output generates a 404 error that displays in its place.

= My Question Is Not Answered Here! =
If your question is not listed here please look on: [http://www.hitreach.co.uk/wordpress-plugins/pure-html/](http://www.hitreach.co.uk/wordpress-plugins/pure-html/ "Web Design Arbroath") and if the answer is not listed there, just leave a comment!


== Change Log ==
= 0.0.1 =
Initial Release
= 1.0.0 =
Addition of custom 404 message
Ability to hide 404 message completely
Beta release of the Allow PHP and Allow JS integration

== Screenshots ==

== Upgrade Notice ==

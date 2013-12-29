=== Profiles ===
Contributors: jwriteclub
Donate link: http://compu.terlicio.us/
Tags: people, users, profiles, profile viewer, bios, biographies
Requires at least: 2.0
Tested up to: 2.5.1
Stable tag: 2.0.RC1

Profiles is a complete biography / profile management system. Profiles does not require that each profile be associated with a WordPress user.

== Description ==

Profiles allows the easy display and management of personal profiles / biographies, or any other similarly periodic information. In particular, it adds a "profiles" tab to the management section and allows you to easily edit information about the profiles as well as associate an image (with optional watermark) with each profile.

Profiles can be used to create browsable pages for almost any kind of periodic information, for example, rental properties at a rental company, aircraft at a flight school, etc, in addition to it's more common use to create biographies/profiles for people.

Support at [Profiles Home Page](http://compu.terlicio.us/code/plugins/profiles/).

Please post any questions or bugs to [Christopher O'Connell](http://compu.terlicio.us/about-contact/). Feature requests and comments of a more general nature are welcome, however, they will likely not be implemented immediately.

== Installation ==

1. Upload the `profiles` folder to the `/wp-content/plugins/` directory.
1. Create a `profiles.php` in your theme folder. Example files for both k2 and the default theme are included (just remove the .k2 or .default from the file name). It is important that the content is encased in a div with class of "entry-content" *if* you wish to use the display side javascript.
1. Create a blank page with 'Profiles (Do Not Use Manually)' as the template. Remember the page slug. (ex. if you created a page titled "People", the page would live at http://url.com/people, 'people' would be the slug.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Visit "Settings > Profiles" and update options as appropriate. **Make sure** that you update the permalinks section.
1. If you opted to use pretty permalinks, visit "Settings > Permalinks". Make sure that you have some permalinking scheme selected (you don't need to change your scheme, just have one selected). Hit "Save >". (**Note:** even if you don't change your permalinking scheme, you still need to select save, so that the new rules required by Profiles are written to the .htaccess file).
1. Visit "Manage > Profiles"
1. Start adding profiles.

**Customization Ideas:**
The `/images` folder contains the loading images as well as the default image for a profile which has no had an image loaded. You can generate custom loading.gif (for the admin menu) and ajax-loader-gif (for the display page) at [AjaxLoad](http://www.ajaxload.info/).

New fonts can be added for watermarking in the `/fonts` folder.

== Frequently Asked Questions ==

= Is 'profiles' "ready for prime time"? =

Absolutely, more or less. In theory, this is a stable, bug free release (see knwon issues under the "Other Notes" tab). In actuality, there may still be a few bugs, but all the major features should work.

It should be noted the image watermarking is still somewhat experimental. It usually works, but some php installs cause it to flake out. This is under investigations, but is not considered a priority for the version 2.0 release.

= Why version 2.0? What about 1.0? =

The various versions which have lived at [Mr|Tots](http://mrtots.com/) and other sites over the years were never released to the public, still there was a codebase, different from the current incarnation, hence, I have chosen that this is version 2.0.

= Where can I get help? =

Either post a topic in the plugin support forum here (I try to stay up to date), or post a comment on the [plugin page](http://compu.terlicio.us/code/plugins/profiles/).

== Screenshots ==

Nothing here now.

== Notes ==

*    See a version (old version) in action at [Mr|Tots](http://mrtots.com/blog/people).
*    Watermarking either works well, or not at all. In either case, the watermark is added when the image is first uploaded. Thus, disabling/enabling watermarking will not modify images already in the db.

== Known Issues ==

*    The "Manage > Profiles" menu appears to have some issues under internet explorer. Theses are under investigation. The display section works flawlessly under IE. In the meantime, use !IE for administration.
*    Support at [Profiles Home Page](http://compu.terlicio.us/code/plugins/profiles/).
*    Please post any questions or bugs to [Christopher O'Connell](http://compu.terlicio.us/about-contact/). Feature requests and comments of a more general nature are welcome, however, they will likely not be implemented immediately.
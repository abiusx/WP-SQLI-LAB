=== Plugin Name ===
Contributors: dliem
Donate link: http://dliem.wordpress.com/2008/10/30/wp-audio-gallery-playlist
Tags: wp, playlist, xspf, audio, gallery, mp3
Requires at least: 2.6
Tested up to: 2.8
Stable tag: 0.11

Audio Player which uses XSPF.  This plugin will embed the XSPF player with a simple insertion of an "href" audio insert, or will insert all audio tracks associated with a post if the tag [WP AUDIO PLAYLIST] is added into your post or page.

== Description ==

Audio Player which uses XSPF.  This plugin will embed the XSPF player with a simple insertion of an "href" audio insert, or will insert all audio tracks associated with a post if the tag [WP AUDIO PLAYLIST] is added into your post or page.

This plugin supports ordering of tracks in the XSPF player and attaching cover
art to a particular track.

updates:
April 13, 2009 [ver. 0.09] - added options for customizing the size of the embedded XSPF player

June 1, 2009 [ver. 0.10] - added option for disabling auto embedding of XSPF for a single mp3 "insertion"

June 12, 2009 [ver. 011] - fixed "undefined playlist" issue. (hardcoded prefix table bug) Thanks to Joseph Bly (<a href="http://www.raggedrogues.com/">http://www.raggedrogues.com/</a>) for helping resolve this issue!

June 22, 2009 [ver. 0.12] - take 2 on the "undefined playlist issue.  hardcoded prefix still existed in playlist.php.  i have fixed that one.

== Installation ==

1. install plugins folder "wp-audio-gallery-playist" into "wp-content/plugins/" folder
2. activate plugin.
3. add text "[WP AUDIO PLAYLIST]" anywhere in to your post, this will add a player with ALL the songs you’ve uploaded to your post.
4. -or- simply insert track into your post from "Add Audio" (with "a href="..." insert /a )
     (if you choose not to have a player auto embedded for a single track.  you may disable this in the plugin preferences)


== Frequently Asked Questions ==

Q: I have updated to version 0.10, and not the xspf player will not auto insert when inserting a single track.

A: Try deactivating the plugin, then activating once again.  This usually does the trick.

== Screenshots ==



== Arbitrary section ==

Features:

1. Ordering your playlist: you can do this by setting the "order" field in
audio gallery of your post.

2. Assign cover/album art for each track in the XSPF player:  upload the
picture anywhere you want (i like to upload it with the post), copy the URI of
the image, and paste it into the caption/excerpt field of the audio file you
are attaching with your audio track.

3. Customize the size of the the XSPF player

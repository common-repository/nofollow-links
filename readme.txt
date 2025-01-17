=== Nofollow Links ===
Contributors: shellab
Donate link: http://blog.andrewshell.org/nofollow-links
Tags: nofollow, link, links, blogroll, seo
Requires at least: 2.3
Tested up to: 5.1.1
Stable tag: 1.0.12

Select which links in your blogroll you want to nofollow.

== Description ==

This is a plugin designed to allow you to append nofollow to the rel attribute of selected links in your blogroll.

It does not conflict with the link relationships (XFN) specified when you edit a link.  It will just be appended to the end of the rel attribute when it's displayed.

== Installation ==

1. Upload `nofollow-links` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Navigate to Links > Nofollow Links and select links you want nofollowed
1. Click "Mark Links Nofollow" button to save your selection

== Frequently Asked Questions ==

= How can I get support? =

Got a bug to report? Or an enhancement to recommend? Or perhaps even some code to submit for inclusion in the next release? Great! Share your feedback with the author, [Andrew Shell](http://blog.andrewshell.org/contact-andrew).

= Who provided the translations? =

* Lithuanian translation was provided by [Vincent Grinius](http://www.host1plus.com/)
* Serbo-Croatian translation was provided by [Andrijana Nikolic](http://webhostinggeeks.com/)

== Screenshots ==

1. Choosing which links to nofollow

== Changelog ==

= 1.0.12 =
* Transitioned stored serialized values to json to prevent security vulnerabilities
* Tested on WordPress 4.9.8

= 1.0.11 =
* Fixed code standard issues including many new string escapes
* Tested on WordPress 4.5

= 1.0.10 =
* Minor CSS changes to support WordPress 4.4

= 1.0.9 =
* Added Serbo-Croatian language

= 1.0.8 =
* Added filter to enable the link manager if not already enabled

= 1.0.7 =
* Switched to using roles instead of user levels to prevent deprecated notices

= 1.0.6 =
* Added Lithuanian translation

= 1.0.5 =
* Added support for [i18n](http://codex.wordpress.org/I18n_for_WordPress_Developers)

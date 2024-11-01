=== Social Accounts ===
Contributors: ffto
Tags: widget, shortcode, social, profiles, accounts, account
Requires at least: 3.4
Tested up to: 3.5
Stable tag: 1.1.2

Add a new section under Settings for your social accounts. The order and the images can be customized with ease.

== Description ==

*Social Accounts* lets you set and show your Social accounts easily.

With this plugin you can:

* Use one of the 2 pre-loaded set of icons (16px and 32px)
* Update account icons by using the Wordpress media manager by clicking on the icon itself.
* Re-order the list of accounts with a simple drag-drop functionality.
* Add custom accounts (with custom image).
* Show the account listing as a widget
* Add the accounts with the shortcode `[social_accounts]`
* Updatable account names
* Support WPML

A little bit more than 25 default accounts are there:

* Behance
* Blogger
* Codepen
* Delicious
* DeviantART
* Dribbble
* Facebook
* Flickr
* Forrst
* Foursquare
* Github
* Google+
* Instagram
* Last.fm
* LinkedIn
* MySpace
* Orkut
* Pinterest
* Plurk
* Slideshare
* Tumblr
* Twitter
* Vimeo
* WordPress
* Yelp
* Youtube
* Email
* Gmail
* Blog RSS feed

== Installation ==

1. Upload Social Accounts to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Visit the settings page under `Settings > Social Accounts` and set your Accounts

== Frequently Asked Questions ==

= There's not less than 10 default icons?? =

In the settings at the top of the page, change the **Visible accounts** to **All Accounts**

= How to re-order the accounts =

When you hover the title of the accounts, an **move** pointer will show, you can then simply drag and drop.

= How to change an account icon =

Click on the icon, the Wordpress media manager popup will show and you'll be able to select the new icon to use.

= How remove the custom icon image =

An **x** will be visible at the top right corner of the custom image. Click on it to revert back to the default image.

= How do I add this into the PHP code =

Insert this `<?php if (function_exists('the_social_accounts')) the_social_accounts(); ?>` in the the template files.

== Screenshots ==

1. Setting page
2. Drag-drop functionality
3. Custom account with custom image
4. WPML Support

== Changelog ==

= 1.1.2 =
* Fix the image replacement not working
* Add optional content position for widget

= 1.1 =
* Updated admin design
* Added the *name* field to change the name of the account
* Added WPML Support
* Added new parameter to the **shortcode** and the **theme functions**

= 1.0 =
* Initial release
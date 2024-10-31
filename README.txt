=== Refined Practice - Simple Analytics Integration ===
Contributors: Meester_Paul
Donate link: 
Tags: analytics, simple analytics, ClassicPress
Requires at least: 4.9
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 1.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use the privacy respecting Simple Analytics service on your site without having to alter any templates. Supports user exclusion and event tracking.

== Description ==

This plugin adds the code needed to use the Simple Analytics analytics service on your site. You can also choose to prevent logged in users from appearing in your analytics, add Simple Analytic's experimental event tracking code and view page views directly on your dashboard.

**Note that use of Simple Analytics requires a subscription** Find out more on the [Simple Analytics website](https://simpleanalytics.com/).

== Screenshots ==

1. The dashboard widget
2. Settings page

== Frequently Asked Questions ==

= Is this the official Simple Analytics plugin? =

No, this plugin was created by [Refined Practice](https://www.refinedpractice.com/).


= How does this differ from the official Simple Analytics plugin =

This plugin offers more features than the official Simple Analytics plugin, in particular:

* Greater flexibility in choosing which logged in users should appear in analytics
* Event tracking code
* Dashboard widget 

= Do I need a subscription =

Yes, find out more on the [Simple Analytics website](https://simpleanalytics.com/).

== WP-CLI ==

This plugin fully supports command-line integration via [WP-CLI](https://wp-cli.org/). Type `wp rpsa` at the command-line for details.

== Get Involved ==

You can report issues, request features and fork the code for this plugin on [GitHub](https://github.com/Refined-Practice/rp-simple-analytics).

== Credits ==

This plugin was developed using the excellent [WordPress Plugin Boilerplate Generator](https://wppb.me/) which you can also find on [GitHub](https://github.com/DevinVinson/WordPress-Plugin-Boilerplate).

== Installation ==

Any of the following methods should work, just choose what's best for your setup:

* *Via your WordPress Dashboard Method 1:* Navigate to the `Plugins` page of your website, click `Add New`, search for `rp-simple-analytics` and click on `Install Now`. Once installed, activate the plugin from your `Plugins` page.
* *Via your WordPress Dashboard Method 1:* Download the plugin zip file from its [WordPress.org plugin page](https://wordpress.org/plugins/rp-simple-analytics), navigate to the `Plugins` page of your website, click `Add New`, click on `Upload Plugin`, choose the zip file you just downloaded and click on `Install Now`. Once installed, activate the plugin from your `Plugins` page.
* *Via [WP-CLI](https://wp-cli.org/):* At your command-line type `wp plugin install rp-simple-analytics --activate` 
* *Manually:* Download the plugin zip file from its [WordPress.org plugin page](https://wordpress.org/plugins/rp-simple-analytics), unzip it and then upload the entire `rp-simple-analytics` folder into your plugins directory.  Once installed, activate the plugin from your website's `Plugins` page.

== Upgrade Notice ==

= 1.2.0 =
Version 1.2.0 updates the event trigger code and includes an improved example automated event tracking script.

== Changelog ==

= 1.2.0 =
* Updated example events javascript to use latest version of [automated events](https://docs.simpleanalytics.com/automated-events)
* Updated event trigger js snippet to latest version
* Updated `<noscript>` alternative snippet
* Tested up to WordPress 5.7

= 1.1.0 =
* ADDED FEATURE: [WP-CLI](https://wp-cli.org/) support. Type `wp rpsa` at the command-line for details
* BUG FIX: Incorrect escaping of additional events javascript

= 1.0.2 =
* Added screenshots to documentation

= 1.0.1 =
* Updated js script version numbers to match latest Simple Analytics release
* Updated documentation

= 1.0.0 =
* First release.


=== User Sync For Klaviyo ===
Contributors: oakandbeechdev
Tags: user, sync, klaviyo, profiles
Donate link: https://oakandbeech.com
Requires at least: 5.0
Tested up to: 6.2
Requires PHP: 5.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will automatically sync your users from WordPress to Klaviyo. 

It will also optionally add the Klaviyo Javascript snippet to your WordPress website.

== Description ==
Once you activate this plugin and enter your Klaviyo API keys, it will automatically sync your WordPress user profiles with Klaviyo.

Each time a new profile is created or updated, an event it also recorded on the profile in Klaviyo.

The following properties are synchronized to Klaviyo as profile properties automatically

  * User ID: `wordpress_user_id`
 * User Registered Date: `wordpress_user_registered`
 * User Login: `wordpress_user_login`
 * User Role: `wordpress_user_role`
 * User First Name
 * User Last Name

A \"**WordPress - Created User**\" and \"**WordPress - Updated User**\" event is recorded when a user is created or updated.

By default this plugin will only synchronize users created or updated after the plugin was enabled. There is also a feature to allow you to manually sync all of your WordPress users as a once off activity.

**Add Klaviyo Onsite Javascript**
This plugin includes a setting to automatically add the Klaviyo Javascript to your website. This is an optional setting, as you may have already added the Klaviyo Javascript via their official integration.

== Installation ==
= Automatic installation =

The automatic installation is the easiest way to install the plugin. You can install the plugin within your WordPress dashboard from the same browser window.
 
From your admin dashboard, go to Plugins > Add New. From the search box, type “GDPR Cookie consent” or just “gdpr” and then search. Click the install button on the GDPR Cookie Consent by CookieYes and then activate the plugin.

= Manual installation =

In the manual installation, you will need to download the zip file of the plugin from the plugin page in WordPress.org. You can upload the file directly from your WordPress dashboard, or using an FTP application.

When doing a manual installation

1.  Upload the plugin folder to the /wp-content/plugins/ directory.
2.  Activate the plugin through the Plugins menu in WordPress.

= Plugin updates =

For every update of the plugin, you will be notified of the installed plugins page. You can directly update the plugin from your dashboard. We recommend that you keep the latest version of the plugin so that you can avail yourself of the new functionalities and security features.


== Frequently Asked Questions ==
= What users will this sync to Klaviyo? =
Every user that is updated or created after the plugin is activated will be automatically synced to Klaviyo.

= What information does this sync to Klaviyo? = 
The plugin will sync
* Email Address
* First Name
* Last Name
* User Role
* User Created At Date
* User Last Updated Date
* User ID
* User username

= Will all users be synchronized to Klaviyo? =
By default only users updated or created after the plugin was activated will be synced. The plugin does have a feature to allow you to perform a bulk sync of all your existing profiles to ensure Klaviyo is up to date.

= What Metrics will be synchronized to Klaviyo? =
This plugin will add two metrics to your Klaviyo account. \"WordPress - Created User\" and \"WordPress - Updated User\".

= Will this plugin add the Klaviyo Javascript to my website? =
By default the plugin will not add the Klaviyo Javascript to your website. You can turn this setting on, and the script will be added to the Footer of your website automatically.

= Will this plugin let me use Klaviyo forms? = 
Once you enable the setting to add the Klaviyo Javascript to your website, you will be able to use the Klaviyo forms feature.

= Will this plugin delete my users in Klaviyo if I remove it? =
No, this plugin will not delete any data from your Klaviyo account at any stage.

== Screenshots ==
1. The settings page of the plugin
2. The information synced by the plugin

== Changelog ==
= 1.0 =
Initial release of the plugin
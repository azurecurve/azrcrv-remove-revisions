=== Remove Revisions ===

Description:	Remove old post revisions over the specified months old.
Version:		1.0.0
Tags:			revisions,maintenance
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Contributors:	azurecurve
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/remove-revisions/
Download link:	https://github.com/azurecurve/azrcrv-remove-revisions/releases/download/v1.0.0/azrcrv-remove-revisions.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	code
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Remove old post revisions over the specified months.

== Description ==

# Description

While revisions can be disabled or limited in number with settings in the wp-config file, there is no way to allow for the deletion of revisions over a certain ago. That is what this plugin allows you to do.

In the options you can set the number of months after which revisions are to be deleted. They can then be deleted at the click of a button or via a cron job running on a daily schedule.

The options also allow you to select the post types (both standard and custom) which can have revisions removed.

Removal of revisions is done using the ClassicPress function to ensure they are done correctly.

This plugin is multisite compatible, with options set on a per site basis.

== Installation ==

# Installation Instructions

 * Download the plugin from [GitHub](https://github.com/azurecurve/azrcrv-remove-revisions/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder and can also be downloaded from the plugin page on https://development.azurecurve.co.uk; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-remove-revisions/releases/v1.0.0)
 * Initial release.

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://codepotent.com/classicpress/plugins/update-manager/) for fully integrated, no hassle, updates.

Some of the top plugins available from **azurecurve** are:
* [Add Open Graph Tags](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/)
* [Add Twitter Cards](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/)
* [Breadcrumbs](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/)
* [Disable FLoC](https://development.azurecurve.co.uk/classicpress-plugins/disable-floc/)
* [Series Index](https://development.azurecurve.co.uk/classicpress-plugins/series-index/)
* [SMTP](https://development.azurecurve.co.uk/classicpress-plugins/smtp/)
* [To Twitter](https://development.azurecurve.co.uk/classicpress-plugins/to-twitter/)
* [Theme Switcher](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/)
* [Toggle Show/Hide](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/)
* [Update Admin Menu](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/)
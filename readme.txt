=== Quick Donations ===
Contributors: Propellex
Tags: Donations for WooCommerce, Charity Plugin for WooCommerce
Requires at least: 5.6
Tested up to: 6.6
Requires PHP: 7.2
Stable tag: 1.1.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A custom plugin that adds a modal to bypass the single product page for Donations for WooCommerce.

== Description ==

Quick Donations is an add-on for the "Donations for WooCommerce" plugin. This plugin enables a modal popup that allows users to donate directly from a campaign grid without navigating to a single product page. This streamlines the donation process and enhances the user experience.

**Features:**
* Display campaigns in a grid format.
* Allows users to donate directly from the grid using a modal popup.
* Compatible with the "Donations for WooCommerce" plugin.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/your-plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Make sure you have WooCommerce and the Donations for WooCommerce plugin installed and activated.

== Usage ==

To use this plugin, you need to add the `[campaign_grid]` shortcode to any page or post. The shortcode will display a grid of donation campaigns with modal popups for direct donations.

**Shortcode:**

`[campaign_grid id="123"]`

**Attributes:**

* `id`: (Required) The ID of the campaign you want to display. Replace `123` with the actual campaign ID.

**Example Usage:**

```plaintext
[campaign_grid id="456"]

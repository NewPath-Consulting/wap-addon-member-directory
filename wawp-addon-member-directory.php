<?php
/**
 * Plugin Name:       WAP Member Directory Add-on
 * Description:       Add a Wild Apricot Member Directory to your website!
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           1.0b1
 * Author:            NewPath Consulting
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wap-addon-member-directory
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/writing-your-first-block-type/
 */

require_once("vendor/autoload.php");
require_once("ContactsAPI.php");

use PO\Admin\AdminSettings;
use PO\classes\ContactsUtils;
use PO\classes\ContactsListingPersistor;
use PO\classes\UserProfileShortcode;
use WAWP\Activator;

new ContactsAPI();
new UserProfileShortcode();

//$activator_dir = wp_normalize_path(ABSPATH . 'wp-content/plugins/wawp/src/Activator.php');
//require_once ($activator_dir);
require_once plugin_dir_path(__FILE__) . '../Wild-Apricot-Press/src/Activator.php';


function create_block_wawp_addon_member_directory_block_init() {
	register_block_type_from_metadata( plugin_dir_path(__FILE__) . 'blocks/member-directory' );
	register_block_type_from_metadata(plugin_dir_path(__FILE__) . 'blocks/member-profile');
	if (!class_exists('WAWP\Addon')) {
		deactivate_plugins(plugin_basename(__FILE__));
		add_action('admin_notices', 'memdir_wawp_not_loaded');
	}
}

add_filter('no_texturize_shortcodes', 'shortcodes_to_exempt_from_wptexturize');
function shortcodes_to_exempt_from_wptexturize($shortcodes) {
	$shortcodes[] = 'wa-contacts';
	return $shortcodes;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');
function add_action_links($links) {
	$mylinks = array(
		'<a href="' . admin_url('admin.php?page=wawp-licensing') . '">Settings</a>',
		
	);
	return array_merge($links, $mylinks);
}

function memdir_wawp_not_loaded() {
	printf(
		'<div class="error"<p>%s</p></div>',
		__('This plugin requires that Wild Apricot Press (WAP) is installed.')
	);
}

add_action( 'init', 'create_block_wawp_addon_member_directory_block_init' );

if (class_exists('WAWP\Activator')) {
	$activator = new WAWP\Activator('wawp-addon-member-directory', plugin_basename(__FILE__), 'WAP Member Directory Add-on');
}

<?php
/**
 * Plugin Name:       WAWP Member Directory Addon
 * Description:       Add a Wild Apricot Member Directory to your website!
 * Requires at least: 5.7
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            NewPath Consulting
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wawp-addon-member-directory
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
// use WAWP\Activator;

new ContactsAPI();
new UserProfileShortcode();

const SLUG = 'wawp-addon-member-directory'; 
const SHOW_NOTICE_ACTIVATION = 'show_notice_activation_' . SLUG;
const LICENSE_CHECK = 'license-check-' . SLUG;
const NAME = 'WAWP Member Directory Addon';

// require_once ($activator_dir);

add_action( 'init', 'create_block_wawp_addon_member_directory_block_init' );
function create_block_wawp_addon_member_directory_block_init() {
	if (!class_exists('WAWP\Addon')) {
		deactivate_plugins(plugin_basename(__FILE__));
		add_action('admin_notices', 'memdir_wawp_not_loaded');
		return;
	}
	register_block_type_from_metadata( plugin_dir_path(__FILE__) . 'blocks/member-directory' );
	register_block_type_from_metadata(plugin_dir_path(__FILE__) . 'blocks/member-profile');
}

add_filter('no_texturize_shortcodes', 'shortcodes_to_exempt_from_wptexturize');
function shortcodes_to_exempt_from_wptexturize($shortcodes) {
	$shortcodes[] = 'wa-contacts';
	return $shortcodes;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links');
function add_action_links($links) {
	$mylinks = array(
		'<a href="' . admin_url('options-general.php?page=waconnector_options') . '">Settings</a>',
	);
	return array_merge($links, $mylinks);
}

function wawp_not_loaded_notice_msg() {
	echo "<div class='error'><p><strong>";
	echo NAME . '</strong> requires that Wild Apricot for Wordpress is installed and activated.</p></div>';
	unset($_GET['activate']);
	return;
}



// put this in plugins_loaded action
if (class_exists('WAWP\Addon')) {
	WAWP\Addon::instance()::new_addon(array(
		'slug' => SLUG,
		'name' => 'WAWP Member Directory Add-on',
		'filename' => plugin_basename(__FILE__),
		'license_check_option' => 'license-check-' . SLUG,
		'show_activation_notice' => 'show_notice_activation_' . SLUG,
		'is_addon' => 1,
		'blocks' => array(
			'wawp-member-addons/member-directory',
			'wawp-member-addons/member-profile'
		)
	));
	// $activator = new WAWP\Activator('wawp-addon-member-directory', plugin_basename(__FILE__), 'WAWP Member Directory Add-on');
}
<?php
/**
 * Plugin Name:       NewPath WildApricotPress Add-on – Member Directory
 * Description:       Add a native Wild Apricot Member Directory to your website
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            NewPath Consulting
 * License:           GPLv2 or later
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

use WAWP\Memdir_Block\classes\ContactsUtils;
use WAWP\Memdir_Block\classes\ContactsListingPersistor;
use WAWP\Memdir_Block\classes\UserProfileShortcode;
// use WAWP\Activator;

new ContactsAPI();
new UserProfileShortcode();

const WAWP_MEMDIR_SLUG = 'wap-addon-member-directory'; 
const WAWP_MEMDIR_SHOW_NOTICE_ACTIVATION = 'show_notice_activation_' . WAWP_MEMDIR_SLUG;
const WAWP_MEMDIR_LICENSE_CHECK = 'license-check-' . WAWP_MEMDIR_SLUG;
const WAWP_MEMDIR_NAME = 'WAP Member Directory Addon';


/**
 * Init hook.
 * Register the blocks if WAWP is loaded and there is a valid license for this plugin.
 */
add_action( 'init', 'create_block_wawp_addon_member_directory_block_init' );
function create_block_wawp_addon_member_directory_block_init() {
	if (!class_exists('WAWP\Addon')) {
		add_action('admin_init', 'wawp_memdir_not_loaded_die');
		return;
	}
	$license_valid = WAWP\Addon::instance()::has_valid_license(WAWP_MEMDIR_SLUG);
	if (!$license_valid) return;
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
		'<a href="' . admin_url('admin.php?page=wap-licensing') . '">Settings</a>',

	);
	return array_merge($links, $mylinks);
}

/**
 * Error message for if WAWP is not installed or activated.
 */
function wawp_memdir_not_loaded_notice_msg() {
	echo '<div class="notice notice-error"><p><strong>';
	echo esc_html(WAWP_MEMDIR_NAME) . '</strong> requires that Wild Apricot for Wordpress is installed and activated.</p></div>';
	unset($_GET['activate']);
	return;
}

/**
 * Deactivates the plugin and adds error message to admin_notices.
 */
function wawp_memdir_not_loaded_die() {
	deactivate_plugins(plugin_basename(__FILE__));
	add_action('admin_notices', 'wawp_memdir_not_loaded_notice_msg');
}

/**
 * Adds the plugin to the list of addons.
 * This is outside of a function because the addon needs to be added on every page because the lifetime of the static Addon instance is for one page.
 */
if (class_exists('WAWP\Addon')) {
	WAWP\Addon::instance()::new_addon(array(
		'slug' => WAWP_MEMDIR_SLUG,
		'name' => WAWP_MEMDIR_NAME,
		'filename' => plugin_basename(__FILE__),
		'license_check_option' => WAWP_MEMDIR_LICENSE_CHECK,
		'show_activation_notice' => WAWP_MEMDIR_SHOW_NOTICE_ACTIVATION,
		'is_addon' => 1,
		'blocks' => array(
			'wawp-member-addons/member-directory',
			'wawp-member-addons/member-profile'
		)
	));
}	

/**
 * Activation function.
 * Checks if WAWP is loaded. Deactivate if not.
 * Calls Addon::activate() function which checks for a license key and sets appropriate flags.
 */
register_activation_hook(plugin_basename(__FILE__), 'wawp_memdir_activate');
function wawp_memdir_activate() {
	if (!class_exists('WAWP\Addon')) {
		add_action('admin_init', 'wawp_memdir_not_loaded_die');
		return;
	}

	WAWP\Addon::instance()::activate(WAWP_MEMDIR_SLUG);
}

/**
 * Deactivation function.
 * Deletes the plugin from the list of WAWP plugins in the options table.
 */
register_deactivation_hook(plugin_basename(__FILE__), 'wawp_memdir_deactivate');
function wawp_memdir_deactivate() {
	// remove from addons list
	$addons = get_option('wawp_addons');
	unset($addons[WAWP_MEMDIR_SLUG]);
	update_option('wawp_addons', $addons);
}
?>

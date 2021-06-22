<?php
/**
 * Plugin Name:       WAWP Member Directory Addon
 * Description:       Example block written with ESNext standard and JSX support – build step required.
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



use WAWP\Activator;

$activator_dir = ABSPATH . 'wp-content/plugins/wawp/src/Activator.php';

require_once ($activator_dir);

function create_block_wawp_addon_member_directory_block_init() {
	register_block_type_from_metadata( __DIR__ );
	if (!class_exists('WAWP\Addon')) {
		deactivate_plugins(plugin_basename(__FILE__));
		add_action('admin_notices', 'memdir_wawp_not_loaded');
	}
}

function memdir_wawp_not_loaded() {
	printf(
		'<div class="error"<p>%s</p></div>',
		__('This plugin requires that Wild Apricot for Wordpress is installed.')
	);
}

add_action( 'init', 'create_block_wawp_addon_member_directory_block_init' );

if (class_exists('WAWP\Activator')) {
	$activator = new WAWP\Activator('wawp-addon-member-directory', plugin_basename(__FILE__), 'WAWP Member Directory Add-on');
}
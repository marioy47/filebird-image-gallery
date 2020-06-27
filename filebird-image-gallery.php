<?php
/**
 * Filebird Image Gallery
 *
 * Creates a shortcode that allows you to embed an image gallery in a page. And the images are selected by a [Filebird](https://wordpress.org/plugins/filebird/) category.
 *
 * @link              https://marioyepes.com
 * @since             1.0.0
 * @package           Filebird_Image_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Filebird Image Gallery
 * Plugin URI:        https://marioyepes.com
 * Description:       Creates a shortcode that allows you to embed an image gallery in a page. And the images are selected by a [Filebird](https://wordpress.org/plugins/filebird/) category.
 * Version:           1.0.0
 * Author:            Mario Yepes
 * Author URI:        https://marioyepes.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       fb-image-gal
 * Domain Path:       /languages
 */

namespace Filebird_Gallery;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Rename this, and start at version 1.0.0
 *
 * @link https://semver.org
 */
define( 'FILEBIRD_IMAGE_GALLERY_VERSION', get_file_data( __FILE__, array( 'Version' => 'Version' ), false ) ['Version'] );

require_once __DIR__ . '/vendor/autoload.php';

Shortcode_Filebird_Gallery::factory()->wp_hooks();


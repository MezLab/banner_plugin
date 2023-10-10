<?php
/*
 * Plugin Name
 *
 * @package           MezCraft
 * @author            Massimo Maestri
 * @copyright         2023 MezCraft
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Mezcraft - Gestione Logisticamente
 * Plugin URI:        https://www.mezcraft.it/
 * Description:       Gestione Risorse e Banner
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Massimo Maestri (MEZ)
 * Author URI:        https://www.mezcraft.it/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mezcraft
 * Domain Path:       /languages
 */


require 'Core/Options.php';
register_activation_hook( __FILE__, 'risorse_plugin_create_table' );
register_activation_hook( __FILE__, 'banner_activation' );


define( 'WPMEZ_PLUGIN_PATH', plugin_dir_path(__FILE__)); 
define( 'WPMEZ_PLUGIN_URL', plugin_dir_url(__FILE__) );

require 'Core/function.php';
require 'route.php';
require 'Core/Shortcode.php';
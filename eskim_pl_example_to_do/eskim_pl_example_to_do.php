<?php
/**
 * Plugin Name:       TO DO by Eskim Example
 * Plugin URI:        https://eskim.pl
 * Description:       Prosta lista TO DO
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Maciej Włodarczak
 * Author URI:        https://eskim.pl
 * License:           GPL v3 or later
 * Text Domain:       eskim_pl_example_to_do
 * Domain Path:       /languages
 */

/**
 * Sprawdzenie czy wtyczka została zainicjowana przez WordPressa. Jeżeli nie zwróci błąd 404.
 */
if ( !function_exists( 'add_action' ) ) {

    header("HTTP/1.0 404 Not Found");
    die();
}

require_once( ABSPATH . 'wp-includes/pluggable.php' );

include_once plugin_dir_path( __FILE__ ).'/src/db.php';
include_once plugin_dir_path( __FILE__ ).'/src/ajax.php';

add_action('admin_menu', function () {

    add_menu_page(
        'TO DO',
        'TO DO',
        'manage_options',
        'eskim_pl_example_to_do',
        function () {
            include_once plugin_dir_path(__FILE__) . '/views/main.php';
        },
        'dashicons-calendar',
        20);
});


add_action('admin_enqueue_scripts', function ($hook) {

    if ( strpos ($hook, 'eskim_pl_example_to_do') !== 0) return;
    wp_enqueue_style('eskim_pl_example_to_do_bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('eskim_pl_example_to_do_bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
});



register_activation_hook (__FILE__, [ 'eskim_pl_example_to_do_DB', 'CREATE' ] );
register_uninstall_hook (__FILE__, [ 'eskim_pl_example_to_do_DB', 'DELETE' ] );



 ?>
<?php
/**
 * Plugin Name: Complete News Publishing
 * Description: A complete plugin for embedding news into your website.
 * Version: 1.0
 * Author: Rizwan Aziz
 * Author URI: http://pkvehicles.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include stylesheet
wp_register_style('compnp_style', plugins_url('style/style.css', __FILE__));
wp_enqueue_style('compnp_style');

// Function to be called on plugin activation
register_activation_hook(__FILE__, 'compnp_activate');
function compnp_activate() {
    global $wpdb;
    
    // Create news table
    $wpdb->query("
    	CREATE TABLE IF NOT EXISTS compnp_news (
            news_id int(11) NOT NULL auto_increment,
            news_content text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
            news_link text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
            news_order int(11) NOT NULL default '0',
            news_status char(3) NOT NULL default 'No',
            news_date datetime NOT NULL default '0000-00-00 00:00:00',
            PRIMARY KEY  (`news_id`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
	");
    
    // Check for empty table
    $result = $wpdb->get_results("SELECT news_id FROM compnp_news WHERE news_id <> ''");
    if (count($result) == 0) {
        // Insert sample news
        $wpdb->query("INSERT INTO compnp_news SET news_content = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', news_link = '#', news_order = '1', news_status = 'Yes', news_date = '0000-00-00 00:00:00' ");
        $wpdb->query("INSERT INTO compnp_news SET news_content = 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', news_link = '#', news_order = '2', news_status = 'Yes', news_date = '0000-00-00 00:00:00' ");
        $wpdb->query("INSERT INTO compnp_news SET news_content = 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', news_link = '#', news_order = '3', news_status = 'Yes', news_date = '0000-00-00 00:00:00' ");
        $wpdb->query("INSERT INTO compnp_news SET news_content = 'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', news_link = '#', news_order = '4', news_status = 'Yes', news_date = '0000-00-00 00:00:00' ");
    }
    
    // Set delay
    update_option('compnp_delay', '3000');
}

// Function to be called on plugin deactivation
register_deactivation_hook(__FILE__, 'compnp_deactivate');
function compnp_deactivate() {
    // Delete time set for delay
    delete_option('compnp_delay');
}

// Function to be called if plugin uninstalled
register_uninstall_hook(__FILE__, 'compnp_uninstall');
function compnp_uninstall() {
    global $wpdb;
    // Delete news table
    $wpdb->query("DROP TABLE compnp_news");
}

// Create settings page
add_action('admin_menu', 'compnp_mainSettings');
function compnp_mainSettings() {
    if (is_admin()) {
        add_options_page( 'Complete News Publishing Settings', 'News Publishing', 'manage_options', 'compnp_mainSettingsSlug', 'compnp_displaySettingsPage' );
    }
}
function compnp_displaySettingsPage() {
    $compnp_page = isset($_GET['compnp_page']) ? $_GET['compnp_page'] : '';
    switch($compnp_page)
    {
        case 'add':
            require_once('pages/add-news.php');
            break;
        case 'edit':
            require_once('pages/edit-news.php');
            break;
        default:
            require_once('pages/settings.php');
            break;
    }
}

// Add shortocde
add_shortcode('CNP_HERE', 'compnp_display');
function compnp_display() {
    global $wpdb;
    // Include js file
    do_action('wp_enqueue_scripts');
    // Set content variable
    $compnp_content = '<script type="text/javascript" language="javascript">function compnp_initializeValues() {';
    $News = $wpdb->get_results("SELECT * FROM compnp_news WHERE news_status = 'Yes'", ARRAY_A);
    for ($i = 0; $i < count($News); $i++) {
        $compnp_content .= ' compnp_targets[' . $i . '] = "' . $News[$i]['news_link'] . '";compnp_newsContent[' . $i . '] = "' . $News[$i]['news_content'] . '";';
    }
    $compnp_content .= '} var compnp_delay = ' . get_option('compnp_delay') . ';</script>';
    $compnp_content .= '<div><a href="" id="compnp_target"></a></div>';
    return $compnp_content;
}

// Ad front end script
add_action('wp_enqueue_scripts', 'compnp_adScript');
function compnp_adScript() {
    wp_register_script('compnp_show_news', plugins_url('js/compnp.js', __FILE__), array('jquery'), false, false);
    wp_enqueue_script('compnp_show_news');
}

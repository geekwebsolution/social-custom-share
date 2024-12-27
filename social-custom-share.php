<?php
/*
Plugin Name: Social Custom Share
Description: Using this plugin, we can allow user to share any text on social media.
Author: Geek Code Lab
Version: 1.4.0
Author URI: https://geekcodelab.com/
Text Domain : social-custom-share
Domain Path: /languages
*/

if(!defined('ABSPATH')) exit;

if(!defined("GWSSCS_PLUGIN_DIR_PATH"))
    define( 'GWSSCS_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

if(!defined("GWSSCS_PLUGIN_URL"))
	define("GWSSCS_PLUGIN_URL",plugins_url().'/'.basename(dirname(__FILE__)));

if (!defined("GWSSCS_PLUGIN_BASENAME")) define("GWSSCS_PLUGIN_BASENAME", plugin_basename(__FILE__));
if (!defined("GWSSCS_PLUGIN_DIR")) define("GWSSCS_PLUGIN_DIR", plugin_basename(__DIR__));

define("GWSSCS_BUILD",'1.4.0');

require(GWSSCS_PLUGIN_DIR_PATH . 'updater/updater.php');

if( ! class_exists( 'Gwsscs_Social_Custom_Share' ) ) {
	class Gwsscs_Social_Custom_Share{
		public function __construct(){
			register_activation_hook( __FILE__, array($this,'reg_activation_callback') );
			add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this,'add_plugin_page_settings_link'));
			add_action( 'admin_footer', array($this,'social_include_admin_js_style'));
            add_action( 'get_footer', array($this,'social_enqueue_style_footer'));
			add_action('init', array($this,'load_text_domain'));
			add_action('upgrader_process_complete', 'Gwsscs_updater_activate'); // remove  transient  on plugin  update
		}

		public function reg_activation_callback() {
			Gwsscs_updater_activate();

			$gwsscs_twitter_handle = '';
			$gwsscs_share_page_url = 1;
			$gwsscs_short_url = 0;
			$gwsscs_add_nofollow = 1;
			$gwsscs_show_style = 'style_default';
			$gwsscs_twitter_data = array();
			$gwsscs_twitter_setting = get_option('gwsscs_twitter_options');
			if(!isset($gwsscs_twitter_setting['twitter_handle']))  $gwsscs_twitter_data['twitter_handle'] = $gwsscs_twitter_handle;	
			if(!isset($gwsscs_twitter_setting['share_page_url']))  $gwsscs_twitter_data['share_page_url'] = $gwsscs_share_page_url;
			if(!isset($gwsscs_twitter_setting['short_url']))  $gwsscs_twitter_data['short_url'] = $gwsscs_short_url;
			if(!isset($gwsscs_twitter_setting['add_nofollow']))  $gwsscs_twitter_data['add_nofollow'] = $gwsscs_add_nofollow;
			if(!isset($gwsscs_twitter_setting['show_style']))  $gwsscs_twitter_data['show_style'] = $gwsscs_show_style;
			if(count($gwsscs_twitter_data) > 0)
			{
				update_option( 'gwsscs_twitter_options', $gwsscs_twitter_data );
			}

			$gwsscs_facebook_short_url = 0;
			$gwsscs_facebook_add_nofollow = 1;
			$gwsscs_facebook_show_style = 'style_default';
			$gwsscs_facebook_data = array();
			$gwsscs_facebook_setting = get_option('gwsscs_facebook_options');
			if(!isset($gwsscs_facebook_setting['short_url_facebook']))  $gwsscs_facebook_data['short_url_facebook'] = $gwsscs_facebook_short_url;	
			if(!isset($gwsscs_facebook_setting['add_nofollow_facebook']))  $gwsscs_facebook_data['add_nofollow_facebook'] = $gwsscs_facebook_add_nofollow;	
			if(!isset($gwsscs_facebook_setting['show_style_facebook']))  $gwsscs_facebook_data['show_style_facebook'] = $gwsscs_facebook_show_style;
			if(count($gwsscs_facebook_data) > 0)
			{
				update_option( 'gwsscs_facebook_options', $gwsscs_facebook_data );
			}
		}

		public function add_plugin_page_settings_link( $links_array) {
			array_unshift( $links_array, '<a href="' . admin_url( 'options-general.php?page=gwsscs-social-custom-share' ) . '">'.__('Settings','social-custom-share').'</a>' );
			return $links_array;
		}

		public function social_include_admin_js_style(){
            wp_enqueue_style("gwsscs-social-style", GWSSCS_PLUGIN_URL."/assets/css/style.css",'');
        }
        
        public function social_enqueue_style_footer(){
            wp_enqueue_style( 'gwsscs-main-style', GWSSCS_PLUGIN_URL.'/assets/css/main.css','');
        }

		public function load_text_domain() {
			load_plugin_textdomain(
				'social-custom-share', false, basename(dirname(__FILE__)) . '/languages/'
			);
		}
		
		
	}
	new Gwsscs_Social_Custom_Share();
}

require_once( GWSSCS_PLUGIN_DIR_PATH .'options.php' );
require_once( GWSSCS_PLUGIN_DIR_PATH .'shortcodes.php' );

?>
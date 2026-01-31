<?php
/**
 * Plugin Name: Digital Prophets Stage Slider
 * Plugin URI: https://digitalprophets.blog
 * Description: Dynamic "Song of the Day" stage slider integrating Sonaar MP3 Player with Slider Revolution for an epic music showcase experience.
 * Version: 1.0.0
 * Author: Digital Prophets
 * Author URI: https://digitalprophets.blog
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dp-stage-slider
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DPSS_VERSION', '1.0.0');
define('DPSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DPSS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DPSS_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Digital Prophets Stage Slider Class
 */
class Digital_Prophets_Stage_Slider {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_dependencies();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('admin_notices', array($this, 'check_dependencies'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    /**
     * Load plugin dependencies
     */
    private function load_dependencies() {
        require_once DPSS_PLUGIN_DIR . 'includes/class-song-of-day-manager.php';
        require_once DPSS_PLUGIN_DIR . 'includes/class-slider-integration.php';
        require_once DPSS_PLUGIN_DIR . 'includes/class-shortcodes.php';
        require_once DPSS_PLUGIN_DIR . 'includes/class-widgets.php';
        require_once DPSS_PLUGIN_DIR . 'includes/class-admin-settings.php';
        
        // Initialize components
        DPSS_Song_Of_Day_Manager::get_instance();
        DPSS_Slider_Integration::get_instance();
        DPSS_Shortcodes::get_instance();
        DPSS_Widgets::get_instance();
        DPSS_Admin_Settings::get_instance();
    }
    
    /**
     * Load plugin textdomain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'dp-stage-slider',
            false,
            dirname(DPSS_PLUGIN_BASENAME) . '/languages'
        );
    }
    
    /**
     * Register custom taxonomies for Sonaar integration
     */
    public function register_taxonomies() {
        // Check if Sonaar is active
        if (!$this->is_sonaar_active()) {
            return;
        }
        
        // Get Sonaar's post types (could be 'podcast', 'album', 'sr_playlist', etc.)
        $sonaar_post_types = $this->get_sonaar_post_types();
        
        // Register "Song of the Day" category
        $labels = array(
            'name'              => __('Song Collections', 'dp-stage-slider'),
            'singular_name'     => __('Song Collection', 'dp-stage-slider'),
            'search_items'      => __('Search Collections', 'dp-stage-slider'),
            'all_items'         => __('All Collections', 'dp-stage-slider'),
            'parent_item'       => __('Parent Collection', 'dp-stage-slider'),
            'parent_item_colon' => __('Parent Collection:', 'dp-stage-slider'),
            'edit_item'         => __('Edit Collection', 'dp-stage-slider'),
            'update_item'       => __('Update Collection', 'dp-stage-slider'),
            'add_new_item'      => __('Add New Collection', 'dp-stage-slider'),
            'new_item_name'     => __('New Collection Name', 'dp-stage-slider'),
            'menu_name'         => __('Song Collections', 'dp-stage-slider'),
        );
        
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'song-collection'),
            'show_in_rest'      => true,
        );
        
        register_taxonomy('song_collection', $sonaar_post_types, $args);
        
        // Create default "Song of the Day" term if it doesn't exist
        if (!term_exists('song-of-the-day', 'song_collection')) {
            wp_insert_term(
                'Song of the Day',
                'song_collection',
                array(
                    'description' => 'Featured daily songs for the stage slider',
                    'slug'        => 'song-of-the-day',
                )
            );
        }
    }
    
    /**
     * Check for required plugin dependencies
     */
    public function check_dependencies() {
        $missing = array();
        
        if (!$this->is_sonaar_active()) {
            $missing[] = 'Sonaar Music (MP3 Audio Player)';
        }
        
        if (!$this->is_slider_revolution_active()) {
            $missing[] = 'Slider Revolution';
        }
        
        if (!empty($missing)) {
            $message = sprintf(
                __('Digital Prophets Stage Slider requires the following plugins to be installed and activated: %s', 'dp-stage-slider'),
                '<strong>' . implode(', ', $missing) . '</strong>'
            );
            
            echo '<div class="notice notice-warning is-dismissible"><p>' . $message . '</p></div>';
        }
    }
    
    /**
     * Check if Sonaar is active
     */
    private function is_sonaar_active() {
        return class_exists('Sonaar_Music') || 
               is_plugin_active('sonaar-music/sonaar-music.php') ||
               function_exists('sonaar_player');
    }
    
    /**
     * Check if Slider Revolution is active
     */
    private function is_slider_revolution_active() {
        return class_exists('RevSliderFront') || 
               is_plugin_active('revslider/revslider.php');
    }
    
    /**
     * Get Sonaar post types
     */
    private function get_sonaar_post_types() {
        $post_types = array('post'); // Fallback
        
        // Common Sonaar post types
        $possible_types = array('podcast', 'album', 'sr_playlist', 'playlist', 'episode');
        
        foreach ($possible_types as $type) {
            if (post_type_exists($type)) {
                $post_types[] = $type;
            }
        }
        
        return apply_filters('dpss_sonaar_post_types', $post_types);
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        // Always enqueue CSS for any shortcodes on the page
        wp_enqueue_style(
            'dpss-stage-slider',
            DPSS_PLUGIN_URL . 'assets/css/stage-slider.css',
            array(),
            DPSS_VERSION
        );
        
        // Only enqueue JS if slider shortcode or widget is present
        // This prevents conflicts with wallet connections and other scripts
        global $post;
        $load_js = false;
        
        if (is_a($post, 'WP_Post') && (
            has_shortcode($post->post_content, 'dp_song_stage') ||
            has_shortcode($post->post_content, 'dp_song_of_day') ||
            has_shortcode($post->post_content, 'dp_latest_songs') ||
            has_shortcode($post->post_content, 'dp_sonaar_player')
        )) {
            $load_js = true;
        }
        
        // Also check if widget is active
        if (is_active_widget(false, false, 'dpss_song_of_day') || 
            is_active_widget(false, false, 'dpss_latest_songs')) {
            $load_js = true;
        }
        
        // Allow manual override via filter
        $load_js = apply_filters('dpss_load_scripts', $load_js);
        
        if ($load_js) {
            wp_enqueue_script(
                'dpss-stage-slider',
                DPSS_PLUGIN_URL . 'assets/js/stage-slider.js',
                array('jquery'),
                DPSS_VERSION,
                true
            );
            
            wp_localize_script('dpss-stage-slider', 'dpssData', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('dpss_nonce'),
            ));
        }
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages and post edit screens
        if (strpos($hook, 'dp-stage-slider') === false && $hook !== 'post.php' && $hook !== 'post-new.php') {
            return;
        }
        
        wp_enqueue_media();
        
        wp_enqueue_style(
            'dpss-admin',
            DPSS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            DPSS_VERSION
        );
        
        wp_enqueue_script(
            'dpss-admin',
            DPSS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'media-upload'),
            DPSS_VERSION,
            true
        );
        
        wp_localize_script('dpss-admin', 'dpssAdmin', array(
            'ajaxurl'    => admin_url('admin-ajax.php'),
            'nonce'      => wp_create_nonce('dpss_nonce'),
            'pluginUrl'  => DPSS_PLUGIN_URL,
            'previewUrl' => home_url('?dpss_preview=1'),
        ));        
        // Localize script
        wp_localize_script('dpss-stage-slider', 'dpssVars', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('dpss_nonce'),
        ));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        if ('settings_page_dpss-settings' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'dpss-admin',
            DPSS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            DPSS_VERSION
        );
        
        wp_enqueue_script(
            'dpss-admin',
            DPSS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            DPSS_VERSION,
            true
        );
    }
}

/**
 * Initialize the plugin
 */
function dpss_init() {
    return Digital_Prophets_Stage_Slider::get_instance();
}

// Start the plugin
add_action('plugins_loaded', 'dpss_init');

/**
 * Activation hook
 */
register_activation_hook(__FILE__, 'dpss_activate');
function dpss_activate() {
    // Create default options
    $default_options = array(
        'slider_height'        => '800',
        'auto_advance'         => '15',
        'enable_parallax'      => '1',
        'enable_ken_burns'     => '1',
        'autoplay_audio'       => '0',
        'stage_overlay'        => 'stage-lights',
        'enable_particles'     => '1',
    );
    
    add_option('dpss_settings', $default_options);
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Deactivation hook
 */
register_deactivation_hook(__FILE__, 'dpss_deactivate');
function dpss_deactivate() {
    flush_rewrite_rules();
}

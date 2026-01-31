<?php
/**
 * Admin Settings Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class DPSS_Admin_Settings {
    
    private static $instance = null;
    private $option_group = 'dpss_settings';
    private $option_name = 'dpss_options';
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_admin_menu() {
        add_menu_page(
            __('Stage Slider Settings', 'dp-stage-slider'),
            __('Stage Slider', 'dp-stage-slider'),
            'manage_options',
            'dp-stage-slider',
            array($this, 'render_settings_page'),
            'dashicons-slides',
            30
        );
        
        add_submenu_page(
            'dp-stage-slider',
            __('Settings', 'dp-stage-slider'),
            __('Settings', 'dp-stage-slider'),
            'manage_options',
            'dp-stage-slider',
            array($this, 'render_settings_page')
        );
        
        add_submenu_page(
            'dp-stage-slider',
            __('Preview', 'dp-stage-slider'),
            __('Preview', 'dp-stage-slider'),
            'manage_options',
            'dp-stage-slider-preview',
            array($this, 'render_preview_page')
        );
    }
    
    public function register_settings() {
        register_setting(
            $this->option_group,
            $this->option_name,
            array($this, 'sanitize_settings')
        );
        
        // Appearance Section
        add_settings_section(
            'dpss_appearance',
            __('Appearance Settings', 'dp-stage-slider'),
            array($this, 'render_appearance_section'),
            $this->option_group
        );
        
        add_settings_field(
            'default_theme',
            __('Default Stage Theme', 'dp-stage-slider'),
            array($this, 'render_theme_field'),
            $this->option_group,
            'dpss_appearance'
        );
        
        add_settings_field(
            'slider_height',
            __('Slider Height (px)', 'dp-stage-slider'),
            array($this, 'render_height_field'),
            $this->option_group,
            'dpss_appearance'
        );
        
        add_settings_field(
            'enable_parallax',
            __('Enable Parallax Effects', 'dp-stage-slider'),
            array($this, 'render_parallax_field'),
            $this->option_group,
            'dpss_appearance'
        );
        
        add_settings_field(
            'enable_ken_burns',
            __('Enable Ken Burns Effect', 'dp-stage-slider'),
            array($this, 'render_ken_burns_field'),
            $this->option_group,
            'dpss_appearance'
        );
        
        add_settings_field(
            'enable_spotlights',
            __('Enable Spotlight Effects', 'dp-stage-slider'),
            array($this, 'render_spotlights_field'),
            $this->option_group,
            'dpss_appearance'
        );
        
        // Behavior Section
        add_settings_section(
            'dpss_behavior',
            __('Behavior Settings', 'dp-stage-slider'),
            array($this, 'render_behavior_section'),
            $this->option_group
        );
        
        add_settings_field(
            'auto_advance',
            __('Auto Advance (seconds)', 'dp-stage-slider'),
            array($this, 'render_auto_advance_field'),
            $this->option_group,
            'dpss_behavior'
        );
        
        add_settings_field(
            'auto_select_category',
            __('Auto-Select from Category', 'dp-stage-slider'),
            array($this, 'render_category_field'),
            $this->option_group,
            'dpss_behavior'
        );
        
        add_settings_field(
            'fallback_to_newest',
            __('Fallback to Newest Song', 'dp-stage-slider'),
            array($this, 'render_fallback_field'),
            $this->option_group,
            'dpss_behavior'
        );
        
        // Integration Section
        add_settings_section(
            'dpss_integration',
            __('Integration Settings', 'dp-stage-slider'),
            array($this, 'render_integration_section'),
            $this->option_group
        );
        
        add_settings_field(
            'sonaar_post_types',
            __('Sonaar Post Types', 'dp-stage-slider'),
            array($this, 'render_post_types_field'),
            $this->option_group,
            'dpss_integration'
        );
        
        add_settings_field(
            'revslider_alias',
            __('Revolution Slider Alias', 'dp-stage-slider'),
            array($this, 'render_revslider_field'),
            $this->option_group,
            'dpss_integration'
        );
    }
    
    public function sanitize_settings($input) {
        $sanitized = array();
        
        if (isset($input['default_theme'])) {
            $sanitized['default_theme'] = sanitize_text_field($input['default_theme']);
        }
        
        if (isset($input['slider_height'])) {
            $sanitized['slider_height'] = absint($input['slider_height']);
        }
        
        $sanitized['enable_parallax'] = isset($input['enable_parallax']) ? 1 : 0;
        $sanitized['enable_ken_burns'] = isset($input['enable_ken_burns']) ? 1 : 0;
        $sanitized['enable_spotlights'] = isset($input['enable_spotlights']) ? 1 : 0;
        
        if (isset($input['auto_advance'])) {
            $sanitized['auto_advance'] = absint($input['auto_advance']);
        }
        
        if (isset($input['auto_select_category'])) {
            $sanitized['auto_select_category'] = sanitize_text_field($input['auto_select_category']);
        }
        
        $sanitized['fallback_to_newest'] = isset($input['fallback_to_newest']) ? 1 : 0;
        
        if (isset($input['sonaar_post_types'])) {
            $sanitized['sonaar_post_types'] = array_map('sanitize_text_field', $input['sonaar_post_types']);
        }
        
        if (isset($input['revslider_alias'])) {
            $sanitized['revslider_alias'] = sanitize_text_field($input['revslider_alias']);
        }
        
        return $sanitized;
    }
    
    // Section Callbacks
    public function render_appearance_section() {
        echo '<p>' . __('Configure the visual appearance of your stage slider.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_behavior_section() {
        echo '<p>' . __('Control how the slider behaves and selects songs.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_integration_section() {
        echo '<p>' . __('Settings for integrating with Sonaar and Revolution Slider.', 'dp-stage-slider') . '</p>';
    }
    
    // Field Callbacks
    public function render_theme_field() {
        $options = get_option($this->option_name);
        $value = isset($options['default_theme']) ? $options['default_theme'] : 'default';
        
        $themes = array(
            'default'          => __('Default Stage', 'dp-stage-slider'),
            'concert-hall'     => __('Concert Hall', 'dp-stage-slider'),
            'club-lights'      => __('Club Lights', 'dp-stage-slider'),
            'outdoor-festival' => __('Outdoor Festival', 'dp-stage-slider'),
            'minimal'          => __('Minimal', 'dp-stage-slider'),
        );
        
        echo '<select name="' . $this->option_name . '[default_theme]">';
        foreach ($themes as $key => $label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($key),
                selected($value, $key, false),
                esc_html($label)
            );
        }
        echo '</select>';
        echo '<p class="description">' . __('Default stage theme for new sliders.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_height_field() {
        $options = get_option($this->option_name);
        $value = isset($options['slider_height']) ? $options['slider_height'] : 800;
        
        printf(
            '<input type="number" name="%s[slider_height]" value="%d" min="300" max="2000" step="50">',
            $this->option_name,
            esc_attr($value)
        );
        echo '<p class="description">' . __('Default slider height in pixels.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_parallax_field() {
        $options = get_option($this->option_name);
        $value = isset($options['enable_parallax']) ? $options['enable_parallax'] : 1;
        
        printf(
            '<label><input type="checkbox" name="%s[enable_parallax]" value="1" %s> %s</label>',
            $this->option_name,
            checked($value, 1, false),
            __('Enable parallax mouse effects', 'dp-stage-slider')
        );
    }
    
    public function render_ken_burns_field() {
        $options = get_option($this->option_name);
        $value = isset($options['enable_ken_burns']) ? $options['enable_ken_burns'] : 1;
        
        printf(
            '<label><input type="checkbox" name="%s[enable_ken_burns]" value="1" %s> %s</label>',
            $this->option_name,
            checked($value, 1, false),
            __('Enable Ken Burns zoom effect on images', 'dp-stage-slider')
        );
    }
    
    public function render_spotlights_field() {
        $options = get_option($this->option_name);
        $value = isset($options['enable_spotlights']) ? $options['enable_spotlights'] : 1;
        
        printf(
            '<label><input type="checkbox" name="%s[enable_spotlights]" value="1" %s> %s</label>',
            $this->option_name,
            checked($value, 1, false),
            __('Enable animated spotlight effects', 'dp-stage-slider')
        );
    }
    
    public function render_auto_advance_field() {
        $options = get_option($this->option_name);
        $value = isset($options['auto_advance']) ? $options['auto_advance'] : 15;
        
        printf(
            '<input type="number" name="%s[auto_advance]" value="%d" min="0" max="60" step="5">',
            $this->option_name,
            esc_attr($value)
        );
        echo '<p class="description">' . __('Seconds before auto-advancing (0 = disable).', 'dp-stage-slider') . '</p>';
    }
    
    public function render_category_field() {
        $options = get_option($this->option_name);
        $value = isset($options['auto_select_category']) ? $options['auto_select_category'] : '';
        
        $terms = get_terms(array(
            'taxonomy'   => 'song_collection',
            'hide_empty' => false,
        ));
        
        echo '<select name="' . $this->option_name . '[auto_select_category]">';
        echo '<option value="">' . __('None (manual selection)', 'dp-stage-slider') . '</option>';
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                printf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($term->slug),
                    selected($value, $term->slug, false),
                    esc_html($term->name)
                );
            }
        }
        echo '</select>';
        echo '<p class="description">' . __('Auto-select from this category if no manual selection.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_fallback_field() {
        $options = get_option($this->option_name);
        $value = isset($options['fallback_to_newest']) ? $options['fallback_to_newest'] : 1;
        
        printf(
            '<label><input type="checkbox" name="%s[fallback_to_newest]" value="1" %s> %s</label>',
            $this->option_name,
            checked($value, 1, false),
            __('Use newest song if no other selection method succeeds', 'dp-stage-slider')
        );
    }
    
    public function render_post_types_field() {
        $options = get_option($this->option_name);
        $selected = isset($options['sonaar_post_types']) ? $options['sonaar_post_types'] : array();
        
        $possible_types = array('podcast', 'album', 'sr_playlist', 'playlist', 'episode', 'post');
        
        foreach ($possible_types as $type) {
            if (post_type_exists($type)) {
                $checked = in_array($type, $selected);
                printf(
                    '<label><input type="checkbox" name="%s[sonaar_post_types][]" value="%s" %s> %s</label><br>',
                    $this->option_name,
                    esc_attr($type),
                    checked($checked, true, false),
                    esc_html($type)
                );
            }
        }
        echo '<p class="description">' . __('Select which post types to use for songs.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_revslider_field() {
        $options = get_option($this->option_name);
        $value = isset($options['revslider_alias']) ? $options['revslider_alias'] : 'dp-song-stage';
        
        printf(
            '<input type="text" name="%s[revslider_alias]" value="%s" class="regular-text">',
            $this->option_name,
            esc_attr($value)
        );
        echo '<p class="description">' . __('Revolution Slider alias to use for stage slider.', 'dp-stage-slider') . '</p>';
    }
    
    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Show save message
        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'dpss_messages',
                'dpss_message',
                __('Settings saved successfully.', 'dp-stage-slider'),
                'success'
            );
        }
        
        settings_errors('dpss_messages');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="dpss-admin-header">
                <p class="description">
                    <?php _e('Configure the Digital Prophets Stage Slider settings. Use shortcode:', 'dp-stage-slider'); ?>
                    <code>[dp_song_stage]</code>
                </p>
            </div>
            
            <form action="options.php" method="post">
                <?php
                settings_fields($this->option_group);
                do_settings_sections($this->option_group);
                submit_button(__('Save Settings', 'dp-stage-slider'));
                ?>
            </form>
            
            <div class="dpss-admin-sidebar">
                <div class="dpss-info-box">
                    <h3><?php _e('Available Shortcodes', 'dp-stage-slider'); ?></h3>
                    <ul>
                        <li><code>[dp_song_stage]</code> - Main stage slider</li>
                        <li><code>[dp_song_of_day]</code> - Song of the day card</li>
                        <li><code>[dp_latest_songs count="6"]</code> - Latest songs grid</li>
                        <li><code>[dp_sonaar_player]</code> - Enhanced player</li>
                    </ul>
                </div>
                
                <div class="dpss-info-box">
                    <h3><?php _e('Need Help?', 'dp-stage-slider'); ?></h3>
                    <p><?php _e('Visit our documentation or preview the slider to see how it looks.', 'dp-stage-slider'); ?></p>
                    <a href="<?php echo admin_url('admin.php?page=dp-stage-slider-preview'); ?>" class="button">
                        <?php _e('Preview Slider', 'dp-stage-slider'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function render_preview_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php _e('Stage Slider Preview', 'dp-stage-slider'); ?></h1>
            
            <div class="dpss-preview-container">
                <?php
                $song_manager = DPSS_Song_Of_Day_Manager::get_instance();
                $song = $song_manager->get_song_of_day();
                
                if ($song) {
                    echo do_shortcode('[dp_song_stage]');
                } else {
                    echo '<p>' . __('No song available for preview. Please select a Song of the Day first.', 'dp-stage-slider') . '</p>';
                }
                ?>
            </div>
            
            <div class="dpss-preview-controls">
                <h3><?php _e('Current Song of the Day', 'dp-stage-slider'); ?></h3>
                <?php if ($song): ?>
                <p>
                    <strong><?php echo esc_html(get_the_title($song)); ?></strong><br>
                    <a href="<?php echo get_edit_post_link($song); ?>"><?php _e('Edit Song', 'dp-stage-slider'); ?></a>
                </p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'dp-stage-slider') === false) {
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
        
        wp_localize_script('dpss-admin', 'dpssAdmin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('dpss_nonce'),
        ));
    }
}

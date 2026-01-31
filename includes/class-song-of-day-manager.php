<?php
/**
 * Song of the Day Manager
 * 
 * Handles fetching and managing the daily featured song
 */

if (!defined('ABSPATH')) {
    exit;
}

class DPSS_Song_Of_Day_Manager {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('save_post', array($this, 'auto_assign_song_of_day'), 10, 2);
        add_action('wp_ajax_dpss_set_song_of_day', array($this, 'ajax_set_song_of_day'));
        add_action('wp_ajax_dpss_get_latest_song', array($this, 'ajax_get_latest_song'));
        add_action('wp_ajax_nopriv_dpss_get_latest_song', array($this, 'ajax_get_latest_song'));
    }
    
    public function init() {
        // Add meta box to Sonaar post types
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
    }
    
    /**
     * Add meta boxes for song of the day management
     */
    public function add_meta_boxes() {
        $post_types = $this->get_sonaar_post_types();
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'dpss_song_of_day',
                __('Stage Slider Settings', 'dp-stage-slider'),
                array($this, 'render_meta_box'),
                $post_type,
                'side',
                'high'
            );
        }
    }
    
    /**
     * Render meta box
     */
    public function render_meta_box($post) {
        wp_nonce_field('dpss_song_meta', 'dpss_song_meta_nonce');
        
        $is_featured = get_post_meta($post->ID, '_dpss_is_song_of_day', true);
        $featured_date = get_post_meta($post->ID, '_dpss_featured_date', true);
        $stage_theme = get_post_meta($post->ID, '_dpss_stage_theme', true);
        $custom_overlay = get_post_meta($post->ID, '_dpss_custom_overlay', true);
        
        ?>
        <div class="dpss-meta-box">
            <p>
                <label>
                    <input type="checkbox" name="dpss_is_song_of_day" value="1" <?php checked($is_featured, '1'); ?>>
                    <?php _e('Set as Song of the Day', 'dp-stage-slider'); ?>
                </label>
            </p>
            
            <p>
                <label><?php _e('Featured Date:', 'dp-stage-slider'); ?></label>
                <input type="date" 
                       name="dpss_featured_date" 
                       value="<?php echo esc_attr($featured_date ? $featured_date : date('Y-m-d')); ?>" 
                       class="widefat">
            </p>
            
            <p>
                <label><?php _e('Stage Theme:', 'dp-stage-slider'); ?></label>
                <select name="dpss_stage_theme" class="widefat">
                    <option value="default" <?php selected($stage_theme, 'default'); ?>><?php _e('Default Stage', 'dp-stage-slider'); ?></option>
                    <option value="concert-hall" <?php selected($stage_theme, 'concert-hall'); ?>><?php _e('Concert Hall', 'dp-stage-slider'); ?></option>
                    <option value="club-lights" <?php selected($stage_theme, 'club-lights'); ?>><?php _e('Club Lights', 'dp-stage-slider'); ?></option>
                    <option value="outdoor-festival" <?php selected($stage_theme, 'outdoor-festival'); ?>><?php _e('Outdoor Festival', 'dp-stage-slider'); ?></option>
                    <option value="minimal" <?php selected($stage_theme, 'minimal'); ?>><?php _e('Minimal', 'dp-stage-slider'); ?></option>
                    <option value="custom" <?php selected($stage_theme, 'custom'); ?>><?php _e('Custom Overlay', 'dp-stage-slider'); ?></option>
                </select>
            </p>
            
            <p class="dpss-custom-overlay" style="<?php echo $stage_theme === 'custom' ? '' : 'display:none;'; ?>">
                <label><?php _e('Custom Overlay Image URL:', 'dp-stage-slider'); ?></label>
                <input type="url" 
                       name="dpss_custom_overlay" 
                       value="<?php echo esc_url($custom_overlay); ?>" 
                       class="widefat"
                       placeholder="https://example.com/stage-overlay.png">
                <small><?php _e('Upload a semi-transparent PNG with stage graphics', 'dp-stage-slider'); ?></small>
            </p>
            
            <p>
                <button type="button" class="button button-secondary dpss-preview-stage">
                    <?php _e('Preview Stage', 'dp-stage-slider'); ?>
                </button>
            </p>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('[name="dpss_stage_theme"]').on('change', function() {
                if ($(this).val() === 'custom') {
                    $('.dpss-custom-overlay').slideDown();
                } else {
                    $('.dpss-custom-overlay').slideUp();
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Auto-assign song of the day to newest post in category
     */
    public function auto_assign_song_of_day($post_id, $post) {
        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Verify nonce
        if (!isset($_POST['dpss_song_meta_nonce']) || 
            !wp_verify_nonce($_POST['dpss_song_meta_nonce'], 'dpss_song_meta')) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save meta fields
        if (isset($_POST['dpss_is_song_of_day'])) {
            update_post_meta($post_id, '_dpss_is_song_of_day', '1');
            
            // If this is being set as song of the day, unset others
            $this->unset_other_songs_of_day($post_id);
        } else {
            delete_post_meta($post_id, '_dpss_is_song_of_day');
        }
        
        if (isset($_POST['dpss_featured_date'])) {
            update_post_meta($post_id, '_dpss_featured_date', sanitize_text_field($_POST['dpss_featured_date']));
        }
        
        if (isset($_POST['dpss_stage_theme'])) {
            update_post_meta($post_id, '_dpss_stage_theme', sanitize_text_field($_POST['dpss_stage_theme']));
        }
        
        if (isset($_POST['dpss_custom_overlay'])) {
            update_post_meta($post_id, '_dpss_custom_overlay', esc_url_raw($_POST['dpss_custom_overlay']));
        }
    }
    
    /**
     * Unset other songs of the day
     */
    private function unset_other_songs_of_day($except_post_id) {
        global $wpdb;
        
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->postmeta} 
                WHERE meta_key = '_dpss_is_song_of_day' 
                AND post_id != %d",
                $except_post_id
            )
        );
    }
    
    /**
     * Get the current song of the day
     */
    public function get_song_of_day() {
        // Try to get manually set song of the day
        $args = array(
            'post_type'      => $this->get_sonaar_post_types(),
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'meta_query'     => array(
                array(
                    'key'     => '_dpss_is_song_of_day',
                    'value'   => '1',
                    'compare' => '='
                )
            ),
            'orderby'        => 'meta_value',
            'meta_key'       => '_dpss_featured_date',
            'order'          => 'DESC'
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            return $query->posts[0];
        }
        
        // Fallback: Get latest song in "Song of the Day" category
        $term = get_term_by('slug', 'song-of-the-day', 'song_collection');
        
        if ($term) {
            $args = array(
                'post_type'      => $this->get_sonaar_post_types(),
                'posts_per_page' => 1,
                'post_status'    => 'publish',
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'song_collection',
                        'field'    => 'slug',
                        'terms'    => 'song-of-the-day',
                    ),
                ),
                'orderby'        => 'date',
                'order'          => 'DESC'
            );
            
            $query = new WP_Query($args);
            
            if ($query->have_posts()) {
                return $query->posts[0];
            }
        }
        
        // Ultimate fallback: Just get the newest song
        $args = array(
            'post_type'      => $this->get_sonaar_post_types(),
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC'
        );
        
        $query = new WP_Query($args);
        
        return $query->have_posts() ? $query->posts[0] : null;
    }
    
    /**
     * Get song data for slider
     */
    public function get_song_data($post) {
        if (!$post) {
            return null;
        }
        
        $data = array(
            'id'            => $post->ID,
            'title'         => get_the_title($post),
            'excerpt'       => get_the_excerpt($post),
            'content'       => get_the_content(null, false, $post),
            'permalink'     => get_permalink($post),
            'featured_image' => get_the_post_thumbnail_url($post, 'full'),
            'artist'        => '',
            'album'         => '',
            'duration'      => '',
            'audio_url'     => '',
            'stage_theme'   => get_post_meta($post->ID, '_dpss_stage_theme', true) ?: 'default',
            'custom_overlay' => get_post_meta($post->ID, '_dpss_custom_overlay', true),
        );
        
        // Try to get Sonaar-specific data
        $data['artist'] = get_post_meta($post->ID, 'sonaar_artist', true) 
                       ?: get_post_meta($post->ID, 'artist_name', true) 
                       ?: '';
        
        $data['album'] = get_post_meta($post->ID, 'sonaar_album', true) 
                      ?: get_post_meta($post->ID, 'album_title', true) 
                      ?: '';
        
        // Get audio file
        $tracks = get_post_meta($post->ID, 'sonaar_playlist', true);
        if (is_array($tracks) && !empty($tracks)) {
            $first_track = $tracks[0];
            $data['audio_url'] = $first_track['FileOrStream'] ?? $first_track['track_mp3'] ?? '';
            $data['duration'] = $first_track['length'] ?? '';
        }
        
        return $data;
    }
    
    /**
     * AJAX: Set song of the day
     */
    public function ajax_set_song_of_day() {
        check_ajax_referer('dpss_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $post_id = intval($_POST['post_id']);
        
        if (!$post_id) {
            wp_send_json_error('Invalid post ID');
        }
        
        $this->unset_other_songs_of_day($post_id);
        update_post_meta($post_id, '_dpss_is_song_of_day', '1');
        update_post_meta($post_id, '_dpss_featured_date', date('Y-m-d'));
        
        wp_send_json_success('Song of the day set successfully');
    }
    
    /**
     * AJAX: Get latest song
     */
    public function ajax_get_latest_song() {
        $song = $this->get_song_of_day();
        $data = $this->get_song_data($song);
        
        if ($data) {
            wp_send_json_success($data);
        } else {
            wp_send_json_error('No song found');
        }
    }
    
    /**
     * Get Sonaar post types
     */
    private function get_sonaar_post_types() {
        $post_types = array('post');
        $possible_types = array('podcast', 'album', 'sr_playlist', 'playlist', 'episode');
        
        foreach ($possible_types as $type) {
            if (post_type_exists($type)) {
                $post_types[] = $type;
            }
        }
        
        return apply_filters('dpss_sonaar_post_types', $post_types);
    }
}

<?php
/**
 * Shortcodes for Digital Prophets Stage Slider
 */

if (!defined('ABSPATH')) {
    exit;
}

class DPSS_Shortcodes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_shortcode('dp_song_stage', array($this, 'render_stage_slider'));
        add_shortcode('dp_song_of_day', array($this, 'render_song_of_day'));
        add_shortcode('dp_latest_songs', array($this, 'render_latest_songs'));
        add_shortcode('dp_sonaar_player', array($this, 'render_sonaar_player'));
    }
    
    /**
     * Main stage slider shortcode
     * Usage: [dp_song_stage height="800" theme="concert-hall" autoplay="true"]
     */
    public function render_stage_slider($atts) {
        $atts = shortcode_atts(array(
            'height'   => '800',
            'theme'    => 'default',
            'autoplay' => 'false',
            'controls' => 'true',
        ), $atts);
        
        $song_manager = DPSS_Song_Of_Day_Manager::get_instance();
        $song = $song_manager->get_song_of_day();
        $song_data = $song_manager->get_song_data($song);
        
        if (!$song_data) {
            return '<div class="dpss-no-song">' . __('No song available', 'dp-stage-slider') . '</div>';
        }
        
        ob_start();
        include DPSS_PLUGIN_DIR . 'templates/stage-slider.php';
        return ob_get_clean();
    }
    
    /**
     * Song of the day display shortcode
     * Usage: [dp_song_of_day layout="card" show_player="true"]
     */
    public function render_song_of_day($atts) {
        $atts = shortcode_atts(array(
            'layout'      => 'card',
            'show_player' => 'true',
            'show_artwork' => 'true',
            'show_info'   => 'true',
        ), $atts);
        
        $song_manager = DPSS_Song_Of_Day_Manager::get_instance();
        $song = $song_manager->get_song_of_day();
        $song_data = $song_manager->get_song_data($song);
        
        if (!$song_data) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="dpss-song-of-day dpss-layout-<?php echo esc_attr($atts['layout']); ?>">
            <?php if ($atts['show_artwork'] === 'true' && $song_data['featured_image']): ?>
            <div class="dpss-artwork">
                <img src="<?php echo esc_url($song_data['featured_image']); ?>" 
                     alt="<?php echo esc_attr($song_data['title']); ?>">
            </div>
            <?php endif; ?>
            
            <?php if ($atts['show_info'] === 'true'): ?>
            <div class="dpss-info">
                <h3 class="dpss-title"><?php echo esc_html($song_data['title']); ?></h3>
                <?php if ($song_data['artist']): ?>
                <p class="dpss-artist"><?php echo esc_html($song_data['artist']); ?></p>
                <?php endif; ?>
                <?php if ($song_data['excerpt']): ?>
                <p class="dpss-excerpt"><?php echo esc_html($song_data['excerpt']); ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <?php if ($atts['show_player'] === 'true' && $song_data['audio_url']): ?>
            <div class="dpss-player">
                <?php echo do_shortcode('[sonaar_audioplayer albums="' . $song_data['id'] . '" show_playlist="false"]'); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Latest songs grid shortcode
     * Usage: [dp_latest_songs count="6" columns="3" category="song-of-the-day"]
     */
    public function render_latest_songs($atts) {
        $atts = shortcode_atts(array(
            'count'    => '6',
            'columns'  => '3',
            'category' => '',
            'orderby'  => 'date',
            'order'    => 'DESC',
        ), $atts);
        
        $args = array(
            'post_type'      => $this->get_sonaar_post_types(),
            'posts_per_page' => intval($atts['count']),
            'post_status'    => 'publish',
            'orderby'        => $atts['orderby'],
            'order'          => $atts['order'],
        );
        
        if (!empty($atts['category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'song_collection',
                    'field'    => 'slug',
                    'terms'    => $atts['category'],
                ),
            );
        }
        
        $query = new WP_Query($args);
        
        if (!$query->have_posts()) {
            return '';
        }
        
        ob_start();
        ?>
        <div class="dpss-latest-songs dpss-columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php while ($query->have_posts()): $query->the_post(); ?>
            <div class="dpss-song-item">
                <?php if (has_post_thumbnail()): ?>
                <div class="dpss-song-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium'); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="dpss-song-content">
                    <h4 class="dpss-song-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h4>
                    <?php 
                    $artist = get_post_meta(get_the_ID(), 'sonaar_artist', true);
                    if ($artist): 
                    ?>
                    <p class="dpss-song-artist"><?php echo esc_html($artist); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Enhanced Sonaar player shortcode
     * Usage: [dp_sonaar_player song_id="123" autoplay="true" theme="stage"]
     */
    public function render_sonaar_player($atts) {
        $atts = shortcode_atts(array(
            'song_id'  => '',
            'autoplay' => 'false',
            'theme'    => 'default',
            'controls' => 'all',
        ), $atts);
        
        if (empty($atts['song_id'])) {
            $song_manager = DPSS_Song_Of_Day_Manager::get_instance();
            $song = $song_manager->get_song_of_day();
            $atts['song_id'] = $song ? $song->ID : '';
        }
        
        if (empty($atts['song_id'])) {
            return '';
        }
        
        $sonaar_atts = array(
            'albums' => $atts['song_id'],
            'hide_artwork' => 'false',
            'show_playlist' => 'false',
            'player_layout' => 'skin_button',
        );
        
        if ($atts['autoplay'] === 'true') {
            $sonaar_atts['autoplay'] = 'true';
        }
        
        $shortcode = '[sonaar_audioplayer';
        foreach ($sonaar_atts as $key => $value) {
            $shortcode .= ' ' . $key . '="' . esc_attr($value) . '"';
        }
        $shortcode .= ']';
        
        return do_shortcode($shortcode);
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
        
        return $post_types;
    }
}

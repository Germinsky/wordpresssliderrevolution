<?php
/**
 * Slider Revolution Integration
 * 
 * Provides integration hooks and helper functions for Slider Revolution
 */

if (!defined('ABSPATH')) {
    exit;
}

class DPSS_Slider_Integration {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_filter('revslider_slide_set_param', array($this, 'add_custom_params'), 10, 4);
        add_action('wp_ajax_dpss_create_slider', array($this, 'ajax_create_slider'));
    }
    
    public function init() {
        // Register custom Revolution Slider add-on if available
        if (class_exists('RevSliderFunctions')) {
            add_action('revslider_do_ajax', array($this, 'handle_ajax_actions'), 10, 2);
        }
    }
    
    /**
     * Create automated slider for song of the day
     */
    public function create_song_of_day_slider() {
        if (!class_exists('RevSlider')) {
            return false;
        }
        
        $slider_data = array(
            'title'  => 'Digital Prophets Stage - Song of the Day',
            'alias'  => 'dp-song-stage',
            'params' => array(
                'width'            => 1920,
                'height'           => 800,
                'responsiveLevels' => array(1920, 1024, 778, 480),
                'gridwidth'        => array(1920, 1024, 778, 480),
                'gridheight'       => array(800, 600, 500, 400),
                'autoHeight'       => 'off',
                'sliderType'       => 'standard',
                'sliderLayout'     => 'fullwidth',
                'delay'            => 15000,
                'navigation' => array(
                    'arrows' => array(
                        'enable' => false
                    ),
                    'bullets' => array(
                        'enable' => false
                    )
                ),
                'parallax' => array(
                    'type'   => 'mouse',
                    'levels' => array(10, 15, 20, 25, 30)
                ),
                'kenBurns' => array(
                    'enable' => true
                )
            )
        );
        
        // Get song data
        $song_manager = DPSS_Song_Of_Day_Manager::get_instance();
        $song = $song_manager->get_song_of_day();
        $song_data = $song_manager->get_song_data($song);
        
        if (!$song_data) {
            return false;
        }
        
        // Create slide data
        $slide_data = $this->create_slide_data($song_data);
        
        return array(
            'slider' => $slider_data,
            'slide'  => $slide_data
        );
    }
    
    /**
     * Create slide data from song
     */
    private function create_slide_data($song_data) {
        $stage_theme = $song_data['stage_theme'];
        $overlay_url = $this->get_stage_overlay_url($stage_theme, $song_data['custom_overlay']);
        
        $slide = array(
            'params' => array(
                'bgType'   => 'image',
                'bgImage'  => $song_data['featured_image'],
                'bgFit'    => 'cover',
                'bgRepeat' => 'no-repeat',
                'bgPosition' => 'center center',
                'kenBurns' => array(
                    'enable' => true,
                    'duration' => 10000,
                    'ease' => 'Power2.easeOut'
                )
            ),
            'layers' => array()
        );
        
        // Add overlay layer (stage graphics)
        if ($overlay_url) {
            $slide['layers'][] = array(
                'type'  => 'image',
                'image' => $overlay_url,
                'style' => array(
                    'width'  => '100%',
                    'height' => '100%',
                    'opacity' => 0.7,
                    'blend-mode' => 'overlay'
                ),
                'position' => array(
                    'x' => 0,
                    'y' => 0,
                    'z-index' => 5
                ),
                'animation' => array(
                    'in' => 'fade',
                    'out' => 'fade'
                )
            );
        }
        
        // Add spotlight effects
        $slide['layers'] = array_merge($slide['layers'], $this->create_spotlight_layers());
        
        // Add album artwork layer
        if ($song_data['featured_image']) {
            $slide['layers'][] = array(
                'type'  => 'image',
                'image' => $song_data['featured_image'],
                'style' => array(
                    'width'  => '300px',
                    'height' => '300px',
                    'border-radius' => '50%',
                    'box-shadow' => '0 20px 60px rgba(0,0,0,0.5)'
                ),
                'position' => array(
                    'x' => 'left',
                    'y' => 'center',
                    'offset-x' => 100,
                    'z-index' => 20
                ),
                'animation' => array(
                    'in' => array(
                        'type' => 'slideInLeft',
                        'duration' => 1000,
                        'delay' => 500
                    ),
                    'out' => 'fadeOut'
                ),
                'parallax' => array(
                    'enable' => true,
                    'level' => 15
                )
            );
        }
        
        // Add song title layer
        $slide['layers'][] = array(
            'type' => 'text',
            'text' => $song_data['title'],
            'style' => array(
                'font-size' => '80px',
                'font-weight' => 'bold',
                'color' => '#ffffff',
                'text-shadow' => '0 0 30px rgba(255,215,0,0.8), 0 0 60px rgba(255,215,0,0.6)',
                'letter-spacing' => '2px'
            ),
            'position' => array(
                'x' => 'center',
                'y' => 'top',
                'offset-y' => 150,
                'z-index' => 25
            ),
            'animation' => array(
                'in' => array(
                    'type' => 'slideInDown',
                    'duration' => 1200,
                    'delay' => 800
                ),
                'out' => 'fadeOut'
            )
        );
        
        // Add artist/description layer
        if ($song_data['artist'] || $song_data['excerpt']) {
            $text = $song_data['artist'] ? 'by ' . $song_data['artist'] : $song_data['excerpt'];
            
            $slide['layers'][] = array(
                'type' => 'text',
                'text' => $text,
                'style' => array(
                    'font-size' => '32px',
                    'color' => '#f0f0f0',
                    'text-shadow' => '0 2px 10px rgba(0,0,0,0.7)'
                ),
                'position' => array(
                    'x' => 'center',
                    'y' => 'top',
                    'offset-y' => 250,
                    'z-index' => 25
                ),
                'animation' => array(
                    'in' => array(
                        'type' => 'fadeIn',
                        'duration' => 1000,
                        'delay' => 1200
                    ),
                    'out' => 'fadeOut'
                )
            );
        }
        
        // Add play button layer
        $slide['layers'][] = array(
            'type' => 'button',
            'text' => '▶ PLAY SONG OF THE DAY',
            'link' => $song_data['audio_url'] ? 'javascript:dpssPlaySong(' . $song_data['id'] . ');' : $song_data['permalink'],
            'style' => array(
                'font-size' => '24px',
                'padding' => '20px 50px',
                'background' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'color' => '#ffffff',
                'border-radius' => '50px',
                'box-shadow' => '0 10px 30px rgba(102,126,234,0.5)',
                'cursor' => 'pointer',
                'transition' => 'all 0.3s'
            ),
            'position' => array(
                'x' => 'center',
                'y' => 'bottom',
                'offset-y' => 150,
                'z-index' => 30
            ),
            'animation' => array(
                'in' => array(
                    'type' => 'bounceIn',
                    'duration' => 1500,
                    'delay' => 1500
                ),
                'out' => 'fadeOut',
                'loop' => array(
                    'type' => 'pulse',
                    'duration' => 2000
                )
            )
        );
        
        return $slide;
    }
    
    /**
     * Create spotlight effect layers
     */
    private function create_spotlight_layers() {
        $spotlights = array();
        $positions = array(
            array('x' => 'left', 'y' => 'top', 'offset-x' => 50, 'offset-y' => 50),
            array('x' => 'right', 'y' => 'top', 'offset-x' => -50, 'offset-y' => 50),
            array('x' => 'center', 'y' => 'center', 'offset-x' => 0, 'offset-y' => -100),
        );
        
        foreach ($positions as $i => $pos) {
            $spotlights[] = array(
                'type' => 'shape',
                'shape' => 'circle',
                'style' => array(
                    'width' => '400px',
                    'height' => '400px',
                    'background' => 'radial-gradient(circle, rgba(255,215,0,0.3) 0%, transparent 70%)',
                    'filter' => 'blur(40px)'
                ),
                'position' => array_merge($pos, array('z-index' => 10)),
                'animation' => array(
                    'in' => array(
                        'type' => 'fadeIn',
                        'duration' => 2000,
                        'delay' => 300 * ($i + 1)
                    ),
                    'loop' => array(
                        'type' => 'pulse',
                        'duration' => 3000 + ($i * 500),
                        'ease' => 'Power1.easeInOut'
                    )
                )
            );
        }
        
        return $spotlights;
    }
    
    /**
     * Get stage overlay URL based on theme
     */
    private function get_stage_overlay_url($theme, $custom_url = '') {
        if ($theme === 'custom' && $custom_url) {
            return $custom_url;
        }
        
        $overlays = array(
            'default'          => DPSS_PLUGIN_URL . 'assets/images/stage-default.png',
            'concert-hall'     => DPSS_PLUGIN_URL . 'assets/images/stage-concert.png',
            'club-lights'      => DPSS_PLUGIN_URL . 'assets/images/stage-club.png',
            'outdoor-festival' => DPSS_PLUGIN_URL . 'assets/images/stage-festival.png',
            'minimal'          => DPSS_PLUGIN_URL . 'assets/images/stage-minimal.png',
        );
        
        return isset($overlays[$theme]) ? $overlays[$theme] : $overlays['default'];
    }
    
    /**
     * Add custom parameters to Revolution Slider
     */
    public function add_custom_params($value, $name, $slide_id, $slide) {
        // Add custom parameters for Sonaar integration
        return $value;
    }
    
    /**
     * Handle AJAX actions
     */
    public function handle_ajax_actions($action, $data) {
        if ($action === 'dp_refresh_song_slider') {
            $this->refresh_slider_content();
        }
    }
    
    /**
     * AJAX: Create slider programmatically
     */
    public function ajax_create_slider() {
        check_ajax_referer('dpss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        $slider = $this->create_song_of_day_slider();
        
        if ($slider) {
            wp_send_json_success($slider);
        } else {
            wp_send_json_error('Failed to create slider');
        }
    }
    
    /**
     * Get Revolution Slider shortcode
     */
    public function get_slider_shortcode() {
        return '[rev_slider alias="dp-song-stage"]';
    }
    
    /**
     * Refresh slider content with latest song
     */
    public function refresh_slider_content() {
        // This would update the existing slider with new song data
        // Implementation depends on Revolution Slider's API
        return true;
    }
}

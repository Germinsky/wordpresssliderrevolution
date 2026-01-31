<?php
/**
 * WordPress Widgets for Stage Slider
 */

if (!defined('ABSPATH')) {
    exit;
}

class DPSS_Widgets {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('widgets_init', array($this, 'register_widgets'));
    }
    
    public function register_widgets() {
        register_widget('DPSS_Song_Of_Day_Widget');
        register_widget('DPSS_Latest_Songs_Widget');
    }
}

/**
 * Song of the Day Widget
 */
class DPSS_Song_Of_Day_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'dpss_song_of_day',
            __('Song of the Day', 'dp-stage-slider'),
            array(
                'description' => __('Display the current Song of the Day', 'dp-stage-slider')
            )
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Song of the Day', 'dp-stage-slider');
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'card';
        $show_player = !empty($instance['show_player']);
        $show_artwork = !empty($instance['show_artwork']);
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        $shortcode_atts = array(
            'layout="' . esc_attr($layout) . '"',
            'show_player="' . ($show_player ? 'true' : 'false') . '"',
            'show_artwork="' . ($show_artwork ? 'true' : 'false') . '"'
        );
        
        echo do_shortcode('[dp_song_of_day ' . implode(' ', $shortcode_atts) . ']');
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Song of the Day', 'dp-stage-slider');
        $layout = !empty($instance['layout']) ? $instance['layout'] : 'card';
        $show_player = !empty($instance['show_player']);
        $show_artwork = !empty($instance['show_artwork']);
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php _e('Title:', 'dp-stage-slider'); ?>
            </label>
            <input class="widefat" 
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('layout')); ?>">
                <?php _e('Layout:', 'dp-stage-slider'); ?>
            </label>
            <select class="widefat" 
                    id="<?php echo esc_attr($this->get_field_id('layout')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('layout')); ?>">
                <option value="card" <?php selected($layout, 'card'); ?>><?php _e('Card', 'dp-stage-slider'); ?></option>
                <option value="minimal" <?php selected($layout, 'minimal'); ?>><?php _e('Minimal', 'dp-stage-slider'); ?></option>
            </select>
        </p>
        
        <p>
            <input class="checkbox" 
                   type="checkbox" 
                   <?php checked($show_player); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_player')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_player')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_player')); ?>">
                <?php _e('Show Player', 'dp-stage-slider'); ?>
            </label>
        </p>
        
        <p>
            <input class="checkbox" 
                   type="checkbox" 
                   <?php checked($show_artwork); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_artwork')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_artwork')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_artwork')); ?>">
                <?php _e('Show Artwork', 'dp-stage-slider'); ?>
            </label>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['layout'] = !empty($new_instance['layout']) ? sanitize_text_field($new_instance['layout']) : 'card';
        $instance['show_player'] = !empty($new_instance['show_player']);
        $instance['show_artwork'] = !empty($new_instance['show_artwork']);
        return $instance;
    }
}

/**
 * Latest Songs Widget
 */
class DPSS_Latest_Songs_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'dpss_latest_songs',
            __('Latest Songs', 'dp-stage-slider'),
            array(
                'description' => __('Display latest songs grid', 'dp-stage-slider')
            )
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Latest Songs', 'dp-stage-slider');
        $count = !empty($instance['count']) ? $instance['count'] : 6;
        $columns = !empty($instance['columns']) ? $instance['columns'] : 3;
        $category = !empty($instance['category']) ? $instance['category'] : '';
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }
        
        $shortcode_atts = array(
            'count="' . esc_attr($count) . '"',
            'columns="' . esc_attr($columns) . '"'
        );
        
        if ($category) {
            $shortcode_atts[] = 'category="' . esc_attr($category) . '"';
        }
        
        echo do_shortcode('[dp_latest_songs ' . implode(' ', $shortcode_atts) . ']');
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Latest Songs', 'dp-stage-slider');
        $count = !empty($instance['count']) ? $instance['count'] : 6;
        $columns = !empty($instance['columns']) ? $instance['columns'] : 3;
        $category = !empty($instance['category']) ? $instance['category'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php _e('Title:', 'dp-stage-slider'); ?>
            </label>
            <input class="widefat" 
                   id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                   type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')); ?>">
                <?php _e('Number of Songs:', 'dp-stage-slider'); ?>
            </label>
            <input class="tiny-text" 
                   id="<?php echo esc_attr($this->get_field_id('count')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('count')); ?>" 
                   type="number" 
                   min="1" 
                   max="12" 
                   value="<?php echo esc_attr($count); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>">
                <?php _e('Columns:', 'dp-stage-slider'); ?>
            </label>
            <select class="widefat" 
                    id="<?php echo esc_attr($this->get_field_id('columns')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('columns')); ?>">
                <option value="2" <?php selected($columns, 2); ?>>2</option>
                <option value="3" <?php selected($columns, 3); ?>>3</option>
                <option value="4" <?php selected($columns, 4); ?>>4</option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('category')); ?>">
                <?php _e('Category:', 'dp-stage-slider'); ?>
            </label>
            <select class="widefat" 
                    id="<?php echo esc_attr($this->get_field_id('category')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('category')); ?>">
                <option value=""><?php _e('All', 'dp-stage-slider'); ?></option>
                <?php
                $terms = get_terms(array(
                    'taxonomy' => 'song_collection',
                    'hide_empty' => false,
                ));
                if (!is_wp_error($terms) && !empty($terms)) {
                    foreach ($terms as $term) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr($term->slug),
                            selected($category, $term->slug, false),
                            esc_html($term->name)
                        );
                    }
                }
                ?>
            </select>
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['count'] = !empty($new_instance['count']) ? absint($new_instance['count']) : 6;
        $instance['columns'] = !empty($new_instance['columns']) ? absint($new_instance['columns']) : 3;
        $instance['category'] = !empty($new_instance['category']) ? sanitize_text_field($new_instance['category']) : '';
        return $instance;
    }
}

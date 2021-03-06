<?php
namespace IDX\Widgets\Omnibar;

class IDX_Omnibar_Widget_Extra extends \WP_Widget
{
    public function __construct()
    {
        $app = new \NetRivet\Container\Container();
        $this->create_omnibar = new \IDX\Widgets\Omnibar\Create_Omnibar($app);

        $widget_ops = array('classname' => 'IDX_Omnibar_Widget_Extra', 'description' => 'An Omnibar Search Widget with extra fields for use with IDX WordPress Sites');
        parent::__construct('IDX_Omnibar_Widget_Extra', 'Deprecated IMPress Omnibar With Extra Fields (Do Not Use)', $widget_ops);
    }

    public $defaults = array(
        'title' => '',
        'min_price' => 0,
        'styles' => 1,
    );

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        $title = $instance['title'];
        ?>
        <p><label for="<?php echo esc_attr($title);?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title');?>" name="<?php echo $this->get_field_name('title');?>" type="text" value="<?php echo esc_attr($title);?>" /></label></p>
        <p>
            <label for="<?php echo $this->get_field_id('styles');?>"><?php _e('Default Styling?', 'idxbroker');?></label>
            <input type="checkbox" id="<?php echo $this->get_field_id('styles');?>" name="<?php echo $this->get_field_name('styles')?>" value="1" <?php checked($instance['styles'], true);?>>
        </p>
        <?php
}

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['min_price'] = $new_instance['min_price'];
        $instance['styles'] = (int) $new_instance['styles'];
        return $instance;
    }

    public function widget($args, $instance)
    {
        $defaults = $this->defaults;

        $instance = wp_parse_args( (array) $instance, $defaults );
        
        extract($args, EXTR_SKIP);

        if (empty($instance)) {
            $instance = $this->defaults;
        }
        if (!isset($instance['styles'])) {
            $instance['styles'] = $this->defaults['styles'];
        }
        if(!isset($instance['min_price'])){
            $instance['min_price'] = $this->defaults['min_price'];
        }

        echo $before_widget;
        $title = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);

        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }

        $plugin_dir = plugins_url();

        //grab url from database set from get-locations.php
        $idx_url = get_option('idx_results_url');

        // Widget HTML:
        echo $this->create_omnibar->idx_omnibar_extra($plugin_dir, $idx_url, $instance['styles'], $instance['min_price']);
        echo $after_widget;
    }
}

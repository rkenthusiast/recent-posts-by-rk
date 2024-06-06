<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Recent_Posts_Widget extends WP_Widget {

    function __construct() {
        parent::__construct(
            'recent_posts_widget', // Base ID
            esc_html__( 'RK - Recent Posts Widget', 'recent-posts-by-rk' ), // Name
            array( 'description' => esc_html__( 'A Widget to display recent posts', 'recent-posts-by-rk' ), ) // Args
        );
        add_action('wp_enqueue_scripts', array($this, 'add_recent_posts_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'add_admin_recent_posts_scripts'));
    }

    public function add_recent_posts_scripts() {
        // Enqueue any styles or scripts needed
    }

    public function add_admin_recent_posts_scripts() {
        // Enqueue any styles or scripts needed
        wp_enqueue_style( 'my_custom_style', RECENT_POSTS_PLUGIN_DIR_URL . 'node_modules/multiple-select/dist/multiple-select.css', array(), '1.0' );
        wp_enqueue_script( 'my_custom_script', RECENT_POSTS_PLUGIN_DIR_URL . 'node_modules/multiple-select/dist/multiple-select.js', array(), '1.0' );
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5,
        );

        if ( ! empty( $instance['category'] ) && is_array( $instance['category'] ) ) {
            $query_args['category_name'] = implode( ',', array_map( 'sanitize_text_field', $instance['category'] ) );
        }

        if ( ! empty( $instance['tag'] ) && is_array( $instance['tag'] ) ) {
            $query_args['tag'] = implode( ',', array_map( 'sanitize_text_field', $instance['tag'] ) );
        }

        $query_args = apply_filters('recent_posts_widget_query_args', $query_args, $instance);

        $recent_posts = new WP_Query( $query_args );

        do_action('recent_posts_widget_before', $args, $instance);

        if ( $recent_posts->have_posts() ) {
            $counter = 1; // Initialize the counter
            echo '<ul class="recent-posts">';
            while ( $recent_posts->have_posts() ) {
                $recent_posts->the_post();
                $featured_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                $first_tag      = get_the_terms( get_the_ID(), 'post_tag' )[0] ?? null;
                $post_date      = get_the_date( 'F j, Y' );
                $categories     = get_the_category();
                $first_category = $categories[0] ?? null; // Get the first category
                $category_link = $first_category ? get_category_link($first_category->term_id) : '#';

                if(!$featured_image )
                {
                    $featured_image = RECENT_POSTS_PLUGIN_DIR_URL.'assets/images/placeholder.png';

                }

                echo '
                <li class="recent-posts-item">
                    <div class="recent-posts-image">
                        <a href="'.get_the_permalink().'"
                            rel="bookmark"><img src="'.esc_url($featured_image).'"
                                class="side-item-thumb wp-post-image" alt="" decoding="async" loading="lazy"></a>
                        <span class="postnum">'.sprintf('%02d', $counter).'</span>
                    </div>
                    <div class="recent-posts-text">
                        <div class="category">
                            <div class="tags no-thumb"><a
                                    href="'.esc_url($category_link).'"
                                    class="tag-link-2">'.($first_category ? $first_category->name : '').'</a></div>
                        </div>
                        <h4><a href="'.get_the_permalink().'"
                                rel="bookmark">'.get_the_title().'</a></h4>
                        <span class="recent-posts-meta">'.$post_date.'</span>
                    </div>
                </li>';

                $counter++; // Increment the counter
            }

            echo '</ul>';
        }

        wp_reset_postdata();

        do_action('recent_posts_widget_after', $args, $instance);

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $number = ! empty( $instance['number'] ) ? $instance['number'] : 5;
        $category = ! empty( $instance['category'] ) ? $instance['category'] : array();
        $tag = ! empty( $instance['tag'] ) ? $instance['tag'] : array();

        // Ensure categories and tags are arrays
        $category = is_array( $category ) ? $category : array();
        $tag = is_array( $tag ) ? $tag : array();

        // Fetch categories
        $categories = get_categories( array( 'hide_empty' => false ) );

        // Fetch tags
        $tags = get_tags( array( 'hide_empty' => false ) );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'recent-posts-by-rk' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Categories:', 'recent-posts-by-rk' ); ?></label>
            <select class="widefat multiple-select" id="<?php echo $this->get_field_id( 'category' ); ?>[]" name="<?php echo $this->get_field_name( 'category' ); ?>[]" multiple>
                <?php foreach ( $categories as $cat ) : ?>
                    <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php echo ( in_array( $cat->slug, $category ) ) ? 'selected' : ''; ?>><?php echo esc_html( $cat->name ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'tag' ); ?>"><?php esc_html_e( 'Tags:', 'recent-posts-by-rk' ); ?></label>
            <select class="widefat multiple-select" id="<?php echo $this->get_field_id( 'tag' ); ?>[]" name="<?php echo $this->get_field_name( 'tag' ); ?>[]" multiple>
                <?php foreach ( $tags as $tag_item ) : ?>
                    <option value="<?php echo esc_attr( $tag_item->slug ); ?>" <?php echo ( in_array( $tag_item->slug, $tag ) ) ? 'selected' : ''; ?>><?php echo esc_html( $tag_item->name ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts:', 'recent-posts-by-rk' ); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        $instance['category'] = ( ! empty( $new_instance['category'] ) && is_array( $new_instance['category'] ) ) ? array_map( 'sanitize_text_field', $new_instance['category'] ) : array();
        $instance['tag'] = ( ! empty( $new_instance['tag'] ) && is_array( $new_instance['tag'] ) ) ? array_map( 'sanitize_text_field', $new_instance['tag'] ) : array();

        return $instance;
    }
}
<?php


namespace BigfootBanner;


abstract class WPOrg_Meta_Box {


    /**
     * Set up and add the meta box.
     */
    public static function add(): void 
    {
        $screens = [ 'post', 'wporg_cpt' ]; // this should become list of fields (as string so we can leverage in callback)
        // possibly keep array of field names in options? Or by calling a func that returns an array...?
        foreach ( $screens as $screen ) {
            add_meta_box(
                'wporg_box_id',          // Unique ID
                'Custom Meta Box Title', // Box title
                [ self::class, 'html' ],   // Content callback, must be of type callable
                $screen                  // Post type
            );
        }
    }


    /**
     * Save the meta box selections.
     *
     * @param int $post_id  The post ID.
     */
    public static function save( int $post_id ): void
    {
        if ( array_key_exists( 'wporg_field', $_POST ) ) {
            update_post_meta(
                $post_id,
                '_wporg_meta_key',
                $_POST['wporg_field']
            );
        }
    }


    /**
     * Display the meta box HTML to the user.
     *
     * @param WP_Post $post   Post object.
     */
    public static function html($post ): void
    {
        $value = get_post_meta( $post->ID, '_wporg_meta_key', true );
        ?>
        <label for="wporg_field">Description for this field</label>
        <select name="wporg_field" id="wporg_field" class="postbox">
            <option value="">Select something...</option>
            <option value="something" <?php selected( $value, 'something' ); ?>>Something</option>
            <option value="else" <?php selected( $value, 'else' ); ?>>Else</option>
        </select>
        <?php
    }
}

add_action( 'add_meta_boxes', [ 'WPOrg_Meta_Box', 'add' ] );
add_action( 'save_post', [ 'WPOrg_Meta_Box', 'save' ] );

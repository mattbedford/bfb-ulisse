<?php


namespace BannerTime;


abstract class AddFields {


    /**
     * Set up and add the meta boxes for the banner.
     * We'll have:
     * simple_mode (checkbox)
     * title (text)
     * subtitle (text)
     * image (file)
     * link (text)
     * link_text (text)
     *  
     */
    public static function add(): void 
    {
        $fields = [
            ['name' =>'simple_mode', 'type' => 'checkbox', 'desc' => 'Simple mode will only show the banner message and a close button.'],
            ['name' =>'title', 'type' => 'text', 'desc' => 'The title of the banner.'],
            ['name' =>'subtitle', 'type' => 'text', 'desc' => 'The subtitle of the banner.'],
            ['name' =>'banner_message', 'type' => 'text', 'desc' => 'The message of the banner.'],
            ['name' =>'link', 'type' => 'text', 'desc' => 'The link of the banner.'],
            ['name' =>'link_text', 'type' => 'text', 'desc' => 'The link text of the banner.'],
        ];
        
        foreach($fields as $field) {
            add_meta_box(
                'bannertime_' . $field['name'],  // Unique ID
                'Bannertime ' . $field['name'],   // Box title
                [ self::class, function($post, $field) {
                    self::html($post, $field);
                }],
                'BANNERTIME_POST_TYPE'              // Post type
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
    public static function html($post, $field ): void
    {
        $value = get_post_meta( $post->ID, '_wporg_meta_key', true );

        echo '<label for="bannertime_' . $field['name'] . '">' . $field['desc'] . '</label>';
        echo '<input type="' . $field['type'] . '" name="bannertime_' . $field['name'] . '"';
        echo 'id="bannertime_' . $field['name'] . '" class="postbox" value="' . $value . '">';
       
    }
}

add_action( 'add_meta_boxes', [ 'AddFields', 'add' ] );
add_action( 'save_post', [ 'AddFields', 'save' ] );

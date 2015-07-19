<?php

/**
 * Adds a meta box to the post editing screen
 */
function portfolios_custom_meta() {
    add_meta_box( 'portfolios_meta_normal', __( 'Normal Image (470x470)', 'portfolios-textdomain' ), 'portfolios_meta_normal_callback', 'portfolio' );
    add_meta_box( 'portfolios_meta_horizontal', __( 'Horizontal Image (940x470)', 'portfolios-textdomain' ), 'portfolios_meta_horizontal_callback', 'portfolio' );
    add_meta_box( 'portfolios_meta_vertical', __( 'Vertical Image (470x940)', 'portfolios-textdomain' ), 'portfolios_meta_vertical_callback', 'portfolio' );
    add_meta_box( 'portfolios_meta_large', __( 'Large Image (940x940)', 'portfolios-textdomain' ), 'portfolios_meta_large_callback', 'portfolio' );
}
add_action( 'add_meta_boxes', 'portfolios_custom_meta' );

/**
 * Outputs the content of the meta box
 */
function portfolios_meta_normal_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'portfolios_nonce' );
    $portfolios_stored_meta = get_post_meta( $post->ID );
    ?>
    <p>
        <label for="meta-normal-image" class="portfolios-row-title"><?php _e( '', 'portfolios-textdomain' )?></label>
        <input type="text" name="meta-normal-image" id="meta-normal-image" value="<?php if ( isset ( $portfolios_stored_meta['meta-normal-image'] ) ) echo $portfolios_stored_meta['meta-normal-image'][0]; ?>" /><br />
        <img src="<?php if ( isset ( $portfolios_stored_meta['meta-normal-image'] ) ) echo $portfolios_stored_meta['meta-normal-image'][0]; ?>" />
        <input type="button" id="meta-normal-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'portfolios-textdomain' )?>" />
    </p>
 
    <?php
}
function portfolios_meta_horizontal_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'portfolios_nonce' );
    $portfolios_stored_meta = get_post_meta( $post->ID );
    ?>
    <p>
        <label for="meta-horizontal-image" class="portfolios-row-title"><?php _e( '', 'portfolios-textdomain' )?></label>
        <input type="text" name="meta-horizontal-image" id="meta-horizontal-image" value="<?php if ( isset ( $portfolios_stored_meta['meta-horizontal-image'] ) ) echo $portfolios_stored_meta['meta-horizontal-image'][0]; ?>" /><br />
        <img src="<?php if ( isset ( $portfolios_stored_meta['meta-horizontal-image'] ) ) echo $portfolios_stored_meta['meta-horizontal-image'][0]; ?>" />
        <input type="button" id="meta-horizontal-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'portfolios-textdomain' )?>" />
    </p>
 
    <?php
}
function portfolios_meta_vertical_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'portfolios_nonce' );
    $portfolios_stored_meta = get_post_meta( $post->ID );
    ?>
    <p>
        <label for="meta-vertical-image" class="portfolios-row-title"><?php _e( '', 'portfolios-textdomain' )?></label>
        <input type="text" name="meta-vertical-image" id="meta-vertical-image" value="<?php if ( isset ( $portfolios_stored_meta['meta-vertical-image'] ) ) echo $portfolios_stored_meta['meta-vertical-image'][0]; ?>" /><br />
        <img src="<?php if ( isset ( $portfolios_stored_meta['meta-vertical-image'] ) ) echo $portfolios_stored_meta['meta-vertical-image'][0]; ?>" />
        <input type="button" id="meta-vertical-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'portfolios-textdomain' )?>" />
    </p>
 
    <?php
}
function portfolios_meta_large_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'portfolios_nonce' );
    $portfolios_stored_meta = get_post_meta( $post->ID );
    ?>
    <p>
        <label for="meta-large-image" class="portfolios-row-title"><?php _e( '', 'portfolios-textdomain' )?></label>
        <input type="text" name="meta-large-image" id="meta-large-image" value="<?php if ( isset ( $portfolios_stored_meta['meta-large-image'] ) ) echo $portfolios_stored_meta['meta-large-image'][0]; ?>" /><br />
        <img src="<?php if ( isset ( $portfolios_stored_meta['meta-large-image'] ) ) echo $portfolios_stored_meta['meta-large-image'][0]; ?>" />
        <input type="button" id="meta-large-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'portfolios-textdomain' )?>" />
    </p>
 
    <?php
}

/**
 * Saves the custom meta input
 */
function portfolios_meta_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'portfolios_nonce' ] ) && wp_verify_nonce( $_POST[ 'portfolios_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and saves if needed
    if( isset( $_POST[ 'meta-normal-image' ] ) ) {
        update_post_meta( $post_id, 'meta-normal-image', $_POST[ 'meta-normal-image' ] );
    }
    if( isset( $_POST[ 'meta-horizontal-image' ] ) ) {
        update_post_meta( $post_id, 'meta-horizontal-image', $_POST[ 'meta-horizontal-image' ] );
    }
    if( isset( $_POST[ 'meta-vertical-image' ] ) ) {
        update_post_meta( $post_id, 'meta-vertical-image', $_POST[ 'meta-vertical-image' ] );
    }
    if( isset( $_POST[ 'meta-large-image' ] ) ) {
        update_post_meta( $post_id, 'meta-large-image', $_POST[ 'meta-large-image' ] );
    }
 
}
add_action( 'save_post', 'portfolios_meta_save' );


/**
 * Loads the image management javascript
 */
function portfolios_image_enqueue() {
    global $typenow;
    if( $typenow == 'portfolio' ) {
        wp_enqueue_media();
 
        // Registers and enqueues the required javascript.
        wp_register_script( 'meta-box-image', plugin_dir_url( __FILE__ ) . '/js/meta-box-image.js', array( 'jquery' ) );
        wp_localize_script( 'meta-box-image', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'portfolios-textdomain' ),
                'button' => __( 'Use this image', 'portfolios-textdomain' ),
            )
        );
        wp_enqueue_script( 'meta-box-image' );
    }
}
add_action( 'admin_enqueue_scripts', 'portfolios_image_enqueue' );



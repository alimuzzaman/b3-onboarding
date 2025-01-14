<?php
    /**
     * Content for the 'user approval page'
     *
     * @since 1.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    function b3_user_approval() {

        if ( ! current_user_can( apply_filters( 'b3_user_cap', 'manage_options' ) ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-onboarding' ) );
        }

        echo '<div class="wrap b3 b3__admin">';
        echo sprintf( '<h1 id="b3__admin-title">%s</h1>', get_admin_page_title() );
        echo do_shortcode( '[user-management]' );
    }

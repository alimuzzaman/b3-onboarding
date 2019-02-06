<?php
    
    /**
     * Content for the 'settings page'
     */
    function b3_user_register_settings() {
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Sorry, you do not have sufficient permissions to access this page.', 'b3-login' ) );
        }
        ?>

        <div class="b3 b3__admin">

            <h1><?php _e( 'Onboarding settings', 'b3-onboarding' ); ?></h1>

            <div class="b3__tabs">
                <?php
                    if ( isset( $_GET[ 'tab' ] ) ) {
                        $default_tab = $_GET[ 'tab' ];
                    } else {
                        $default_tab = 'recaptcha';
                    }

                    $tabs        = array(
                        array(
                            'id'      => 'main',
                            'title'   => esc_html__( 'Main', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'main' ),
                            'icon'    => 'admin-site',
                        ),
                        array(
                            'id'      => 'pages',
                            'title'   => esc_html__( 'Pages', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'pages' ),
                            'icon'    => 'admin-page',
                        ),
                        array(
                            'id'      => 'settings',
                            'title'   => esc_html__( 'Settings', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'settings' ),
                            'icon'    => 'admin-generic',
                        ),
                    );
                    // if ( defined( 'WP_ENV' ) && 'development' == WP_ENV ) {
                    //     $tabs[] = array(
                    //         'id'      => 'addon',
                    //         'title'   => esc_html__( 'Addon', 'b3-onboarding' ),
                    //         'content' => b3_render_tab_content( 'addons' ),
                    //         'icon'    => 'plus',
                    //     );
                    // }
                    
                    if ( get_option( 'b3_custom_emails' ) ) {
                        $tabs[] = array(
                            'id'      => 'emails',
                            'title'   => esc_html__( 'Emails', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'emails' ),
                            'icon'    => 'email',
                        );
                    }
                    
                    if ( get_option( 'b3_recaptcha' ) ) {
                        $tabs[] = array(
                            'id'      => 'recaptcha',
                            'title'   => esc_html__( 'Recaptcha', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'recaptcha' ),
                            'icon'    => 'star-filled',
                        );
                    }
                    
                    if ( current_user_can( 'manage_options' ) ) {
                        $tabs[] = array(
                            'id'      => 'debug',
                            'title'   => esc_html__( 'Debug info', 'b3-onboarding' ),
                            'content' => b3_render_tab_content( 'debug' ),
                            'icon'    => 'shield',
                        );
                    }
                ?>
                <div class="b3__tab-header">
                    <?php foreach ( $tabs as $tab ) { ?>
                        <button class="b3__tab-button<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' active' : false; ?>" onclick="openTab(event, '<?php echo $tab[ 'id' ]; ?>')">
                            <?php if ( isset( $tab[ 'icon' ] ) ) { ?>
                                <i class="dashicons dashicons-<?php echo $tab[ 'icon' ]; ?>"></i>
                            <?php } ?>
                            <?php echo $tab[ 'title' ]; ?>
                        </button>
                    <?php } ?>
                </div>

                <div class="tab-contents">
                    <?php foreach ( $tabs as $tab ) { ?>
                        <div id="<?php echo $tab[ 'id' ]; ?>" class="b3__tab-content"<?php echo ( $tab[ 'id' ] == $default_tab ) ? ' style="display: block;"' : false; ?>>
                            <div class="entry-content">
                                <?php if ( $tab[ 'content' ] ) { ?>
                                    <?php echo $tab[ 'content' ]; ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>

        </div>
    <?php }

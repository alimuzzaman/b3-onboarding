<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // check if class already exists
    if ( ! class_exists( 'B3Shortcodes' ) ) {

        /**
         * Class B3Shortcodes
         *
         * @since 2.0.0
         */
        class B3_Shortcodes extends B3Onboarding {
            /**
             * B3_Shortcodes constructor
             */
            public function __construct() {
                parent::__construct();

                add_shortcode( 'account-page',      array( $this, 'b3_render_account_page' ) );
                add_shortcode( 'lostpass-form',     array( $this, 'b3_render_lost_password_form' ) );
                add_shortcode( 'login-form',        array( $this, 'b3_render_login_form' ) );
                add_shortcode( 'register-form',     array( $this, 'b3_render_register_form' ) );
                add_shortcode( 'resetpass-form',    array( $this, 'b3_render_reset_password_form' ) );
                add_shortcode( 'user-management',   array( $this, 'b3_render_user_approval_page' ) );
            }


            /**
             * Renders the register form
             *
             * @since 1.0.0
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return mixed|string|void
             */
            public function b3_render_register_form( $user_variables, $content = null ) {
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'register',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                $registration_type                 = get_option( 'b3_registration_type' );
                $attributes[ 'registration_type' ] = $registration_type;

                if ( is_user_logged_in() && 'blog' != $registration_type ) {
                    return sprintf( '<p class="b3_message">%s</p>', esc_html__( 'You are already logged in.', 'b3-onboarding' ) );
                }

                if ( isset( $_REQUEST[ 'registered' ] ) && 'new_blog' == $_REQUEST[ 'registered' ] ) {
                    if ( isset( $_GET[ 'site_id' ] ) && ! empty( $_GET[ 'site_id' ] ) ) {
                        switch_to_blog( $_GET[ 'site_id' ] );
                        $home_url  = home_url( '/' );
                        $site_info = get_site( $_GET[ 'site_id' ] );
                        $admin_url = apply_filters( 'b3_dashboard_url', admin_url( '/' ), $site_info );
                        restore_current_blog();

                        $message = '<p class="b3_message b3_message--success">';
                        $message .= esc_html__( "Congratulations, you've registered your new site.", 'b3-onboarding' );
                        $message .= '<br>';
                        $message .= esc_html__( 'Visit it on', 'b3-onboarding' ) . ': ';
                        $message .= sprintf( '<a href="%s">%s</a>', esc_url( $home_url ), esc_url( $home_url ) );
                        $message .= '<br>';
                        $message .= sprintf( esc_html__( 'You can manage your new site %s.', 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( $admin_url ), esc_html__( 'here', 'b3-onboarding' ) ) );
                        $message .= '</p>';

                        return $message;
                    } else {
                        // fallback
                        $message = '<p class="b3_message b3_message--success">';
                        $message .= esc_html__( "Congratulations, you've registered your new site.", 'b3-onboarding' );
                        $message .= '</p>';

                        return $message;
                    }
                }

                if ( 'none' == $registration_type && ! current_user_can( 'manage_network' ) ) {
                    // registration closed
                    return sprintf( '<p class="b3_message">%s</p>', apply_filters( 'b3_registration_closed_message', b3_get_registration_closed_message() ) );

                } elseif ( 'blog' == $registration_type && ! is_user_logged_in() ) {
                    // logged in registration only
                    return sprintf( '<p class="b3_message">%s</p>', apply_filters( 'b3_logged_in_registration_only_message', b3_get_logged_in_registration_only_message() ) );

                } else {
                    $attributes[ 'errors' ] = array();
                    if ( isset( $_REQUEST[ 'registration-error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'registration-error' ] );
                        $error_count = 1;
                        foreach ( $error_codes as $error_code ) {
                            if ( 1 == count( $error_codes ) ) {
                                $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code, false );
                            } else {
                                if ( 1 < $error_count ) {
                                    // 2 errors only occurs with extra fields
                                    if ( strpos( $error_code, 'field_' ) !== false ) {
                                        $field_id           = substr( $error_code, 6 );
                                        $extra_field_values = apply_filters( 'b3_extra_fields', array() );
                                        $column             = array_column( $extra_field_values, 'id' );
                                        $key                = array_search( $field_id, $column );
                                        if ( isset( $extra_field_values[ $key ][ 'label' ] ) ) {
                                            $sprintf_variable         = $extra_field_values[ $key ][ 'label' ];
                                            $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_codes[ 0 ], $sprintf_variable );
                                        }
                                    } else {
                                        $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code );
                                    }
                                }
                            }
                            $error_count++;
                        }

                    } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                        if ( 'access_requested' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'dummy' == $_REQUEST[ 'registered' ] ) {
                            // dummy is for demonstration setup
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        }
                    }

                    if ( 1 == get_option( 'b3_activate_recaptcha' ) && 'register' == $attributes[ 'template' ] ) {
                        $recaptcha_public  = get_option( 'b3_recaptcha_public' );
                        $recaptcha_version = get_option( 'b3_recaptcha_version' );

                        $attributes[ 'recaptcha' ] = [
                            'public'  => $recaptcha_public,
                            'version' => $recaptcha_version,
                        ];
                    }

                    B3Onboarding::b3_show_admin_notices();

                    $attributes = apply_filters( 'b3_attributes', $attributes );

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }
            }


            /**
             * A shortcode for rendering the login form.
             *
             * @since 1.0.0
             *
             * @param array $attributes Shortcode attributes.
             * @param string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_login_form( $user_variables, $content = null ) {
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'login',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                }

                // Pass the redirect parameter to the WordPress login functionality: but
                // only if a valid redirect URL has been passed as request parameter, use it.
                $attributes[ 'registration_type' ] = get_option( 'b3_registration_type' );
                $attributes[ 'redirect' ]          = false;

                if ( isset( $_REQUEST[ 'redirect_to' ] ) ) {
                    $attributes[ 'redirect' ] = wp_validate_redirect( $_REQUEST[ 'redirect_to' ], $attributes[ 'redirect' ] );
                }

                $errors = array();
                if ( isset( $_REQUEST[ 'login' ] ) || isset( $_REQUEST[ 'error' ] ) ) {
                    if ( isset( $_REQUEST[ 'login' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'login' ] );
                    } elseif ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    }

                    foreach ( $error_codes as $code ) {
                        $errors[] = $this->b3_get_return_message( $code );
                    }

                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( is_multisite() ) {
                        $attributes[ 'messages' ][] = sprintf( esc_html__( 'You have successfully registered to %s. We have emailed you an activation link.', 'b3-onboarding' ), sprintf( '<strong>%s</strong>', get_site_option( 'site_name' ) ) );
                    } else {
                        if ( 'access_requested' == $_REQUEST[ 'registered' ] ) {
                            // access_requested
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'confirm_email' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'dummy' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( $_REQUEST[ 'registered' ] );
                        } elseif ( 'success' == $_REQUEST[ 'registered' ] ) {
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( 'registration_success' );
                        } else {
                            error_log( 'FIX ELSE - line 116 class-b3-shortcodes.php' );
                            $attributes[ 'messages' ][] = $this->b3_get_return_message( '' );
                        }
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'mu-activate' ] ) && 'success' == $_REQUEST[ 'mu-activate' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'mu_activate_success' );
                } elseif ( isset( $_REQUEST[ 'password' ] ) && 'changed' == $_REQUEST[ 'password' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'password_updated' );
                } elseif ( isset( $_REQUEST[ 'checkemail' ] ) && 'confirm' == $_REQUEST[ 'checkemail' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'lost_password_sent' );
                } elseif ( isset( $_REQUEST[ 'logout' ] ) && 'true' == $_REQUEST[ 'logout' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'logged_out' );
                } elseif ( isset( $_REQUEST[ 'account' ] ) && 'removed' == $_REQUEST[ 'account' ] ) {
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'account_remove' );
                }

                $attributes[ 'errors' ] = $errors;

                $attributes = apply_filters( 'b3_attributes', $attributes );

                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
            }


            /**
             * A shortcode for rendering the password lost form.
             *
             * @since 1.0.0
             *
             * @param array $attributes Shortcode attributes.
             * @param string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_lost_password_form( $user_variables, $content = null ) {
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'lostpassword',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return sprintf( '<p class="b3_message">%s</p>', esc_html__( 'You are already logged in.', 'b3-onboarding' ) );
                }

                $attributes[ 'errors' ] = array();
                if ( isset( $_REQUEST[ 'error' ] ) ) {
                    $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                    foreach ( $error_codes as $error_code ) {
                        $attributes[ 'errors' ][] = $this->b3_get_return_message( $error_code );
                    }
                } elseif ( isset( $_REQUEST[ 'activate' ] ) && 'success' == $_REQUEST[ 'activate' ] ) {
                    // you can now log in... should this be here ?
                    $attributes[ 'messages' ][] = $this->b3_get_return_message( 'activate_success' );
                } elseif ( isset( $_REQUEST[ 'registered' ] ) ) {
                    if ( 'success' == $_REQUEST[ 'registered' ] ) {
                        $attributes[ 'messages' ][] = $this->b3_get_return_message( 'registration_success_enter_password' );
                    }
                }

                $attributes = apply_filters( 'b3_attributes', $attributes );

                return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
            }


            /**
             * A shortcode for rendering the reset password form.
             *
             * @since 1.0.0
             *
             * @param array $attributes Shortcode attributes.
             * @param string $content The text content for shortcode. Not used.
             *
             * @return string  The shortcode output
             */
            public function b3_render_reset_password_form( $user_variables, $content = null ) {
                $default_attributes = array(
                    'title'    => false,
                    'template' => 'resetpass',
                );
                $attributes         = shortcode_atts( $default_attributes, $user_variables );

                if ( is_user_logged_in() ) {
                    return '<p class="b3_message">' . esc_html__( 'You are already logged in.', 'b3-onboarding' ) . '</p>';
                } else {
                    if ( isset( $_REQUEST[ 'login' ] ) && isset( $_REQUEST[ 'key' ] ) ) {
                        $attributes[ 'login' ] = $_REQUEST[ 'login' ];
                        $attributes[ 'key' ]   = $_REQUEST[ 'key' ];
                        $errors                = array();

                        if ( isset( $_REQUEST[ 'error' ] ) ) {
                            $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                            foreach ( $error_codes as $code ) {
                                $errors[] = $this->b3_get_return_message( $code );
                            }
                        }
                        $attributes[ 'errors' ] = $errors;

                        $attributes = apply_filters( 'b3_attributes', $attributes );

                        return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );

                    } else {
                        // error message for password reset
                        $message = esc_html__( 'This is not a valid password reset link.', 'b3-onboarding' );
                        $message .= '<br>';
                        $message .= esc_html__( 'Please click the provided link in your email.', 'b3-onboarding' );
                        $message .= '<br>';
                        $message .= sprintf( esc_html__( "If you haven't received any email, please %s.", 'b3-onboarding' ), sprintf( '<a href="%s">%s</a>', esc_url( b3_get_lostpassword_url() ), esc_html( 'click here', 'b3-onboarding' ) ) );

                        return $message;
                    }
                }
            }


            /**
             * Render user/account page
             *
             * @since 1.0.0
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return bool|string
             */
            public function b3_render_account_page( $user_variables, $content = null ) {
                if ( is_user_logged_in() ) {
                    wp_enqueue_script( 'user-profile' );
                    $errors             = array();
                    $default_attributes = array(
                        'title'    => false,
                        'template' => 'account',
                    );
                    $attributes         = shortcode_atts( $default_attributes, $user_variables );

                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_return_message( $code );
                        }
                    }
                    $attributes[ 'errors' ]            = $errors;
                    $attributes[ 'registration_type' ] = get_option( 'b3_registration_type' );

                    if ( isset( $_REQUEST[ 'updated' ] ) ) {
                        $attributes[ 'updated' ] = $this->b3_get_return_message( $_REQUEST[ 'updated' ] );
                    }

                    $attributes = apply_filters( 'b3_attributes', $attributes );

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }

                return false;
            }


            /**
             * Render user management page
             *
             * @since 1.0.0
             *
             * @param      $user_variables
             * @param null $content
             *
             * @return bool|string
             */
            public function b3_render_user_approval_page( $user_variables, $content = null ) {
                if ( current_user_can( 'promote_users' ) ) {
                    $default_attributes = array(
                        'title'    => false,
                        'template' => 'user-management',
                    );
                    $attributes         = shortcode_atts( $default_attributes, $user_variables );

                    $errors = array();
                    if ( isset( $_REQUEST[ 'error' ] ) ) {
                        $error_codes = explode( ',', $_REQUEST[ 'error' ] );
                        foreach ( $error_codes as $code ) {
                            $errors[] = $this->b3_get_return_message( $code );
                        }
                    }
                    $attributes[ 'errors' ]               = $errors;
                    $attributes[ 'register_email_only' ]  = get_option( 'b3_register_email_only' );
                    $attributes[ 'registration_type' ]    = get_option( 'b3_registration_type' );
                    $attributes[ 'show_first_last_name' ] = get_option( 'b3_activate_first_last' );

                    if ( is_multisite() ) {
                        global $wpdb;
                        $query                 = "SELECT * FROM $wpdb->signups WHERE active = '0'";
                        $attributes[ 'users' ] = $wpdb->get_results( $query );
                    } else {
                        $user_args             = array( 'role' => 'b3_approval' );
                        $attributes[ 'users' ] = get_users( $user_args );
                    }

                    B3Onboarding::b3_show_admin_notices();

                    $attributes = apply_filters( 'b3_attributes', $attributes );

                    return $this->b3_get_template_html( $attributes[ 'template' ], $attributes );
                }

                return false;
            }
        }

        new B3_Shortcodes();
    }

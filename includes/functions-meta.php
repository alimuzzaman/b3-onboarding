<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    /**
     * Return all custom meta keys
     *
     * @since 1.0.0
     *
     * @return array
     */
    function b3_get_all_custom_meta_keys() {

        // Keep this list updated
        $meta_keys = array(
            'b3_account_activated_message',
            'b3_account_activated_subject',
            'b3_account_approved_message',
            'b3_account_approved_subject',
            'b3_account_page_id',
            'b3_account_rejected_message',
            'b3_account_rejected_subject',
            'b3_activate_custom_emails',
            'b3_activate_custom_passwords',
            'b3_activate_filter_validation',
            'b3_activate_first_last',
            'b3_activate_recaptcha',
            'b3_activate_wpmu_user_site_message',
            'b3_activate_wpmu_user_site_subject',
            'b3_activated_wpmu_user_message',
            'b3_activated_wpmu_user_site_message',
            'b3_activated_wpmu_user_site_subject',
            'b3_activated_wpmu_user_subject',
            'b3_approval_page_id',
            'b3_confirm_wpmu_user_message',
            'b3_confirm_wpmu_user_site_message',
            'b3_confirm_wpmu_user_site_subject',
            'b3_confirm_wpmu_user_subject',
            'b3_dashboard_widget',
            'b3_debug_info',
            'b3_disable_action_links',
            'b3_disable_admin_notification_new_user',
            'b3_disable_admin_notification_password_change',
            'b3_disable_delete_user_email',
            'b3_disable_user_notification_password_change',
            'b3_disallowed_usernames',
            'b3_email_activation_message',
            'b3_email_activation_subject',
            'b3_email_styling',
            'b3_email_template',
            'b3_first_last_required',
            'b3_front_end_approval',
            'b3_hide_admin_bar',
            'b3_honeypot',
            'b3_link_color',
            'b3_login_page_id',
            'b3_logo_in_email',
            'b3_logout_page_id',
            'b3_lost_password_message',
            'b3_lost_password_page_id',
            'b3_lost_password_subject',
            'b3_main_logo',
            'b3_new_user_message',
            'b3_new_user_notification_addresses',
            'b3_new_user_subject',
            'b3_new_wpmu_user_admin_message',
            'b3_new_wpmu_user_admin_subject',
            'b3_notification_sender_email',
            'b3_notification_sender_name',
            'b3_preserve_settings',
            'b3_privacy',
            'b3_privacy_page_id',
            'b3_privacy_text',
            'b3_recaptcha_public',
            'b3_recaptcha_secret',
            'b3_recaptcha_version',
            'b3_redirect_set_password',
            'b3_register_email_only',
            'b3_register_page_id',
            'b3_registration_closed_message',
            'b3_registration_type',
            'b3_request_access_message_admin',
            'b3_request_access_message_user',
            'b3_request_access_notification_addresses',
            'b3_request_access_subject_admin',
            'b3_request_access_subject_user',
            'b3_reset_password_page_id',
            'b3_restrict_admin',
            'b3_sidebar_widget',
            'b3_use_popup',
            'b3_users_may_delete',
            'b3_welcome_new_user_content',
            'b3_welcome_new_user_subject',
            'b3_welcome_user_message',
            'b3_welcome_user_subject',
            'b3_welcome_user_message_manual',
            'b3ob_version',
            // check
            'b3_domain_restrictions',
            'b3_disallowed_domains',
        );

        return $meta_keys;
    }

<?php
    /*
     * Input fields for 'user activated with a new site' (MS)
     *
     * @since 3.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    $activated_wpmu_user_site_subject = get_option( 'b3_activated_wpmu_user_site_subject' );
    $activated_wpmu_user_site_message = get_option( 'b3_activated_wpmu_user_site_message' );
    $placeholder_subject              = b3_default_subject_welcome_wpmu_user_blog();
    $placeholder_subject              = strtr( $placeholder_subject, b3_replace_subject_vars() );
    $placeholder_message              = esc_attr( b3_default_message_welcome_wpmu_user_blog() );
?>
<table class="b3_table b3_table--emails">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If a field is left empty the default value will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th>
            <label for="b3__input--activated-wpmu-user-site-subject"><?php esc_html_e( 'Email subject', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <input id="b3__input--activated-wpmu-user-site-subject" name="b3_activated_wpmu_user_site_subject" type="text" placeholder="<?php echo $placeholder_subject; ?>" value="<?php echo esc_attr( $activated_wpmu_user_site_subject ); ?>" />
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--activated-wpmu-user-site-message"><?php esc_html_e( 'Email content', 'b3-onboarding' ); ?></label>
            <br>
            <?php echo b3_get_preview_link( 'mu-user-site-activated' ); ?>
        </th>
        <td>
            <textarea id="b3__input--activated-wpmu-user-site-message" name="b3_activated_wpmu_user_site_message" placeholder="<?php echo $placeholder_message; ?>" rows="6"><?php echo stripslashes( $activated_wpmu_user_site_message ); ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>

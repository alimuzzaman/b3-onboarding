<?php $email_styling = get_option( 'b3_email_styling', false ); ?>
<table class="b3__table b3__table--emails" border="0" cellpadding="0" cellspacing="0">
    <tbody>
    <tr>
        <td colspan="2" class="b3__intro">
            <?php esc_html_e( 'If left empty the default styling will be used.', 'b3-onboarding' ); ?>
        </td>
    </tr>
    <tr>
        <th class="align-top">
            <label for="b3__input--email-styling" class=""><?php esc_html_e( 'Email styling', 'b3-onboarding' ); ?></label>
        </th>
        <td>
            <textarea id="b3__input--email-styling" name="b3_email_styling" rows="4"><?php if ( $email_styling ) { echo $email_styling; } ?></textarea>
        </td>
    </tr>
    <tr>
        <th>&nbsp;</th>
        <td>
            <input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save settings', 'b3-onboarding' ); ?>" />
        </td>
    </tr>
    </tbody>
</table>
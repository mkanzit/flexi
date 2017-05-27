<div class="postbox">
    <h3 class="hndle"><span><?php _e('Advanced features','wp-html-mail'); ?></span></h3>
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <?php /*<tr valign="top">
                    <th scope="row"><label><?php _e('Export Template Settings','wp-html-mail') ?></label></th>
                    <td>
                        <textarea><?php echo stripslashes(str_replace('\\&quot;','',json_encode($theme_options))); ?></textarea>
                    </td>
                </tr>*/ ?>
                <?php
                $theme_is_writable = is_writable(get_stylesheet_directory());
                ?>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Create custom template','wp-html-mail') ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail_create_template">
                        <button type="button" id="haet_mail_create_template_button" class="button-secondary <?php echo ($theme_is_writable?'':'button-disabled'); ?>"><?php _e('create template file in my theme folder', 'wp-html-mail') ?></button>
                        <?php if(file_exists(get_stylesheet_directory().'/wp-html-mail/template.html')): ?>
                            <p><?php _e('You already have a custom template. If you create a new one the existing template will be backed up.','wp-html-mail'); ?></p>
                        <?php endif; ?>
                        <?php if(!$theme_is_writable): ?>
                            <p><?php _e('WARNING: Your theme directory is not writable by the server. Please change the permission to allow us to create the mail template.','wp-html-mail'); ?></p>
                        <?php endif; ?>
                        <p class="description">
                            <?php _e('Customize your mail template as far as you can. Then click this button to export the template to your theme directory for further modifications.<br>The template will be created in <strong>wp-content/YOUR-THEME/wp-html-mail/template.html</strong>','wp-html-mail'); ?>
                        </p>
                        <div id="haet_mail_template_created" class="haet-mail-dialog" title="<?php _e('Template created','wp-html-mail'); ?>">
                            <p>
                                <?php _e('Your template has been created.','wp-html-mail'); ?>
                            </p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
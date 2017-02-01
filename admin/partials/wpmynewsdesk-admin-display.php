<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="wrap">
    <form method="post" action="options.php">
        <?php
        settings_fields('wpmynewsdesk-settings');
        do_settings_sections('wpmynewsdesk-settings');
        submit_button();
        ?>
    </form>

    <hr>

    <h2>Import media</h2>

    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><label for="unique-key">Media types</label></th>
            <td>
                <fieldset>
                    <label for="pressrelease">
                        <input type="checkbox" name="type_of_media[]" value="pressrelease" id="pressrelease" checked>
                        <span><?php esc_attr_e('Pressrelease', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="news">
                        <input type="checkbox" name="type_of_media[]" value="news" id="news" checked>
                        <span><?php esc_attr_e('News', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="blog_post">
                        <input type="checkbox" name="type_of_media[]" value="blog_post" id="blog_post" checked>
                        <span><?php esc_attr_e('Blog Post', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="event">
                        <input type="checkbox" name="type_of_media[]" value="event" id="event" checked>
                        <span><?php esc_attr_e('Event', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="image">
                        <input type="checkbox" name="type_of_media[]" value="image" id="image" checked>
                        <span><?php esc_attr_e('Image', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="video">
                        <input type="checkbox" name="type_of_media[]" value="video" id="video" checked>
                        <span><?php esc_attr_e('Video', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="document">
                        <input type="checkbox" name="type_of_media[]" value="document" id="document" checked>
                        <span><?php esc_attr_e('Document', $this->plugin_name); ?></span>
                    </label>
                </fieldset>

                <fieldset>
                    <label for="contact_person">
                        <input type="checkbox" name="type_of_media[]" value="contact_person" id="contact_person" checked>
                        <span><?php esc_attr_e('Contact Person', $this->plugin_name); ?></span>
                    </label>
                </fieldset>
            </td>
        </tr>
        </tbody>
    </table>

    <button href="#" class="js-wpmnd--import-mynewsdesk button button-primary">Import</button>
    <span class="js-wpmnd--import-mynewsdesk-message"></span>
    <p class="description"><?php esc_html_e('This imports the above selected media types.', $this->plugin_name); ?></p>
</div>
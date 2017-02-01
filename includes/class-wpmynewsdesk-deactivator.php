<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      2.0.0
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_Deactivator
{

    /**
     * Delete the saved Mynewsdesk page on deactivation.
     *
     * @since    2.0.0
     */
    public static function deactivate()
    {
        // Get page id.
        $saved_page_id = get_option('wpmynewsdesk_mynewsdesk_page_id');

        // Check if the saved page ID exists.
        if ($saved_page_id) {

            // Delete saved page.
            wp_delete_post($saved_page_id, true);
            // Delete saved page id record in the database.
            delete_option('wpmynewsdesk_mynewsdesk_page_id');

        }
    }

}

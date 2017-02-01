<?php

/**
 * Fired during plugin activation
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      2.0.0
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_Activator
{
    /**
     * On activation create a page and remember it.
     *
     * Create a page named "Mynewsdesk", with an shortcode that will show all media.
     * Save the page ID to plugin options.
     *
     * @since    2.0.0
     */
    public static function activate()
    {
        $mynewsdesk_page_args = [
            'post_title'   => __('Mynewsdesk', 'wpmynewsdesk-save'),
            'post_content' => '[wp_mynewsdesk]',
            'post_status'  => 'publish',
            'post_type'    => 'page',
        ];

        $mynewsdesk_page_id = wp_insert_post($mynewsdesk_page_args);
        add_option('wpmynewsdesk_mynewsdesk_page_id', $mynewsdesk_page_id);
    }

}

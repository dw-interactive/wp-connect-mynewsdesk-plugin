<?php
/**
 * Class for syncing from Mynewsdesk to WP.
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/admin
 */

/**
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/admin
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_Cron
{

    /**
     * The ID of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Bootstrap class
     */
    function bootstrap()
    {

        // Register CRON action
        add_action('cron_wpmynewsdesk_import', array($this, 'run_cron'), 10, 1);
        $this->register_cron();

        // For debugging only to run on each pageload. TODO: Secure this with auth as well
        if (isset($_GET['trigger_wpmynewsdesk_import'])) {
            $this->run_cron();
        }
    }

    /**
     * Registers the required CRON job
     */
    function register_cron()
    {

        /**
         * News
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'news'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'news']);
        }

        /**
         * Pressrelease
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'pressrelease'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'pressrelease']);
        }

        /**
         * Blog_post
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'blog_post'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'blog_post']);
        }

        /**
         * Event
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'event'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'event']);
        }

        /**
         * Image
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'image'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'image']);
        }

        /**
         * Video
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'video'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'video']);
        }

        /**
         * Document
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'document'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'document']);
        }

        /**
         * Contact_person
         */
        if (!wp_next_scheduled('cron_wpmynewsdesk_import', ['type_of_media' => 'contact_person'])) {
            wp_schedule_event(current_time('timestamp'), 'hourly', 'cron_wpmynewsdesk_import', ['type_of_media' => 'contact_person']);
        }
    }

    /**
     * This function imports Mynewsdesk media into the WordPress.
     *
     * @param $type_of_media
     */
    function run_cron($type_of_media)
    {
        $type_of_media = 'pressrelease';
        echo "Running cron! <br> <br>";

        // Extend time limit to 10 minutes to avoid timeouts.
        set_time_limit(600);

        $params = [
            'format'        => 'json',
            'limit'         => '100',
            'order'         => 'created',
            'type_of_media' => $type_of_media,
        ];

        // Get results from API.
        $result = (new Wpmynewsdesk_Import($this->plugin_name, $this->version))->connect_api($params);
        if ( ! $result->items->item)
            return false;

        // Get latest imported Mynewsdesk ID.
        $mynewsdesk_id = get_option($this->plugin_name . '-latest_id-' . $type_of_media);

        // Delete old latest_id meta.
        delete_option($this->plugin_name . '-latest_id-' . $type_of_media);

        // Loop through results from API.
        foreach ($result->items->item as $key => $value) {

            // Abort if the current media ID matches the latest_id saved in post meta.
            if ($mynewsdesk_id == $value->id) {
                echo "Aborting import since the stored ID is equal to the media ID which is beeing stored now.";
                if (false === get_option($this->plugin_name . '-latest_id-' . $value->type_of_media)) {
                    update_option($this->plugin_name . '-latest_id-' . $value->type_of_media, $value->id);
                }
                die();
            }

            // Import item to the Custom Post Type.
            (new Wpmynewsdesk_Import($this->plugin_name, $this->version))->item($value);

            // Do some spring cleaning...
            gc_collect_cycles();
        }
    }
}
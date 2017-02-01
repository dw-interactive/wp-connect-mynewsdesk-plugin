<?php

/**
 * This class imports the data received from Mynewsdesk in to WordPress Custom Post Type.
 *
 * @since      2.0.0
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_Import
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
     * Imports a single Mynewsdesk item to WordPress.
     *
     * @param $item
     * @return int $post_id
     */
    public function item($item)
    {

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $post_id = $this->create_post($item);
        $this->set_featured_image($item, $post_id);

        // Update post meta with Mynewsdesk media ID
        update_post_meta($post_id, 'mynewsdesk_media_id', $item->id);

        // Create or add the category
        wp_set_object_terms($post_id, $item->type_of_media, 'mynewsdesk_media_category');

        // Create or add the tag(s)
        if($item->tags->tag)
            wp_set_object_terms($post_id, $item->tags->tag, 'mynewsdesk_media_tag');

        // Set the "latest_updated_media" option
//        update_option($this->plugin_name . '-latest_id-' . $item->type_of_media, $item->id);
        if (false === get_option($this->plugin_name . '-latest_id-' . $item->type_of_media)) {
            update_option($this->plugin_name . '-latest_id-' . $item->type_of_media, $item->id);
        }

        return [
            'post_id'  => $post_id,
            'media_id' => $item->id,
        ];
    }

    /**
     * Download, attach and set image from an item as featured image.
     *
     * @param $item
     * @param $post_id
     * @return bool
     */
    protected function set_featured_image($item, $post_id)
    {
        if(!$item->image)
            return false;

        // Download and attach image to post.
        media_sideload_image($item->image, $post_id, $item->image_caption);

        // Then find the last image added to the post attachments.
        $attachment = array_values(get_attached_media('image', $post_id))[0];

        // Finally set image as the post thumbnail.
        if ($attachment) {
            set_post_thumbnail($post_id, $attachment->ID);
            return $attachment->ID;
        }

        return false;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function create_post($item)
    {
        // Build data for the post insert
        $post_arr = array(
            'post_title'    => wp_strip_all_tags($item->header),
            'post_content'  => isset($item->body) ? $item->body : ' ',
            'post_excerpt'  => isset($item->summary) ? $item->summary : ' ',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'mynewsdesk_media',
            'post_date'     => $item->published_at,
            'post_modified' => $item->updated_at,
        );

        // Insert the post into the database.
        $post_id = wp_insert_post($post_arr);
        return $post_id;
    }

    public function connect_api($params)
    {
        // Get the options.
        $options = get_option($this->plugin_name . '-settings');

        // Get the API key.
        $unique_key = $options['unique-key'];

        // Set the pressroom
        $params['pressroom'] = isset($options['select-pressroom']) ? (string)$options['select-pressroom'] : 'se';

        // Build the URL.
        $url = "https://www.mynewsdesk.com/services/pressroom/list/" . $unique_key . "/?format=json&" . http_build_query($params);

        // Get from API.
        $request = wp_remote_get($url);
        return json_decode(wp_remote_retrieve_body($request));
    }
}
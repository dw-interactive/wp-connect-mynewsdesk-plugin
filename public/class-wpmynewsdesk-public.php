<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/public
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_Public
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
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wpmynewsdesk_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wpmynewsdesk_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $options = get_option($this->plugin_name . '-settings');
        if (isset($options['enable-pre-defined-styles'])) {
            wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpmynewsdesk-public.css', [], $this->version, 'all');
        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wpmynewsdesk_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wpmynewsdesk_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

//        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpmynewsdesk-public.js', ['jquery'], $this->version, false);
    }



    /**
     * The master view with all components combined into
     * one view to rule them all.
     *
     * @param array $atts
     * @return bool|void
     */
    public function main_shortcode($atts = array())
    {
        return "Mynewsdesk Shortcode";
    }

    /**
     * Register all the shortcodes.
     *
     */
    public function register_shortcodes()
    {

        add_shortcode('wp_mynewsdesk', [$this, 'main_shortcode']);

    }

}


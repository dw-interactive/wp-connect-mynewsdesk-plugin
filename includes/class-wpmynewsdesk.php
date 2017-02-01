<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      2.0.0
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/includes
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      Wpmynewsdesk_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    2.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function __construct()
    {

        $this->plugin_name = 'wpmynewsdesk';
        $this->version = '2.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Wpmynewsdesk_Loader. Orchestrates the hooks of the plugin.
     * - Wpmynewsdesk_i18n. Defines internationalization functionality.
     * - Wpmynewsdesk_Admin. Defines all hooks for the admin area.
     * - Wpmynewsdesk_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpmynewsdesk-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpmynewsdesk-i18n.php';

        /**
         * The class responsible for importing items.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpmynewsdesk-import.php';

        /**
         * The class responsible running cron jobs.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-wpmynewsdesk-cron.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-wpmynewsdesk-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-wpmynewsdesk-public.php';

        $this->loader = new Wpmynewsdesk_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Wpmynewsdesk_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    2.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Wpmynewsdesk_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {

        $plugin_admin = new Wpmynewsdesk_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_cron = new Wpmynewsdesk_Cron($this->get_plugin_name(), $this->get_version());
//        $plugin_importer = new Wpmynewsdesk_Import($this->get_plugin_name(), $this->get_version());

        /**
         * Enqueue our styles and scripts.
         */
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

        /**
         * Hook our settings page.
         */
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_settings_page');

        /**
         * Hook our settings.
         */
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');

        /**
         * Create the CPT.
         */
        $this->loader->add_action('init', $plugin_admin, 'create_cpt');

        /**
         * Import all Mynewsdesk media to the CPT.
         */
        $this->loader->add_action('wp_ajax_wpmnd_import_mynewsdesk', $plugin_admin, 'import_mynewsdesk');

        /**
         * Clear the CPT through AJAX.
         */
        $this->loader->add_action('wp_ajax_wpmnd_clear_cpt', $plugin_admin, 'clear_cpt');

        /**
         * Bootstrap the cron job.
         */
        $this->loader->add_action('init', $plugin_cron, 'bootstrap');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    2.0.0
     * @access   private
     */
    private function define_public_hooks()
    {

        $plugin_public = new Wpmynewsdesk_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

        // Get list by AJAX
        $this->loader->add_action('wp_ajax_get_list', $plugin_public, 'get_service_list_ajax');
        $this->loader->add_action('wp_ajax_nopriv_get_list', $plugin_public, 'get_service_list_ajax');

        // Register shortcodes
        $this->loader->add_action('init', $plugin_public, 'register_shortcodes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    2.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     2.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     2.0.0
     * @return    Wpmynewsdesk_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     2.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}

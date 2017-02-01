<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/admin
 * @author     Robin Nilsson <robin.nilsson@dwinteractive.se>
 */
class Wpmynewsdesk_Admin
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
     * Register the stylesheets for the admin area.
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

        // Remove admin styles since we are using WP defaults
        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpmynewsdesk-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpmynewsdesk-admin.js', array('jquery'), $this->version, false);

        wp_localize_script(
            $this->plugin_name,
            'wpmynewsdesk_ajax_admin',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('wpmynewsdesk_nonce_admin'),
            )
        );
    }

    /**
     * Register the settings page for the admin area.
     *
     * @since    2.0.0
     */
    public function register_settings_page()
    {
        // Create our settings page as a submenu page.
        add_submenu_page(
            'options-general.php',
            __('Mynewsdesk', 'wpmynewsdesk'),
            __('Mynewsdesk', 'wpmynewsdesk'),
            'manage_options',
            'wpmynewsdesk',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Display the settings page content for the page we have created.
     *
     * @since    2.0.0
     */
    public function display_settings_page()
    {

        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/wpmynewsdesk-admin-display.php';

    }

    /**
     * Register settings fields
     *
     * @since    2.0.0
     */
    public function register_settings()
    {
        // Register setting
        register_setting(
            $this->plugin_name . '-settings',
            $this->plugin_name . '-settings',
            array($this, 'sandbox_register_setting')
        );

        // Register setting section
        add_settings_section(
            $this->plugin_name . '-settings-section',
            __('Settings', $this->plugin_name),
            array($this, 'sandbox_add_settings_section'),
            $this->plugin_name . '-settings'
        );

        // Set the unique key for the API.
        add_settings_field(
            'unique-key',
            __('Unique Key', 'wpmynewsdesk'),
            array($this, 'sandbox_add_settings_field_input_text'),
            $this->plugin_name . '-settings',
            $this->plugin_name . '-settings-section',
            array(
                'label_for'   => 'unique-key',
                'description' => __('This is the unique identifier for the API given by Mynewsdesk.', $this->plugin_name),
            )
        );

        // Enable pre-defined styles?
        add_settings_field(
            'enable-pre-defined-styles',
            __('Enable pre-defined styles?', 'wpmynewsdesk'),
            array($this, 'sandbox_add_settings_field_single_checkbox'),
            $this->plugin_name . '-settings',
            $this->plugin_name . '-settings-section',
            array(
                'label_for' => 'enable-pre-defined-styles',
            )
        );

        // Select Mynewsdesk pressroom.
        add_settings_field(
            'select-pressroom',
            __('Select pressroom', $this->plugin_name),
            array($this, 'sandbox_add_settings_field_select'),
            $this->plugin_name . '-settings',
            $this->plugin_name . '-settings-section',
            array(
                'label_for'      => 'select-pressroom',
                'description'    => __('Select which pressroom to fetch and display.', $this->plugin_name),
                'select_options' => [
                    ['name' => 'Svenska', 'value' => 'se'],
                    ['name' => 'Norska', 'value' => 'no'],
                    ['name' => 'Danska', 'value' => 'dk'],
                ],
            )
        );
    }

    /**
     * Sandbox our settings.
     *
     * @since    2.0.0
     * @param $input
     * @return array
     */
    public function sandbox_register_setting($input)
    {
        $new_input = array();

        if (isset($input)) {
            // Loop trough each input and sanitize the value if the input id isn't post-types
            foreach ($input as $key => $value) {
                if ($key == 'post-types') {
                    $new_input[$key] = $value;
                } else {
                    $new_input[$key] = sanitize_text_field($value);
                }
            }
        }

        return $new_input;

    }

    /**
     * Sandbox our section for the settings.
     *
     * @since    2.0.0
     */
    public function sandbox_add_settings_section()
    {

        return;

    }

    /**
     * Sandbox our single checkboxes.
     *
     * @since    2.0.0
     * @param $args
     */
    public function sandbox_add_settings_field_single_checkbox($args)
    {
        $field_id = $args['label_for'];
        $field_description = $args['description'];

        $options = get_option($this->plugin_name . '-settings');
        $option = 0;

        if ( ! empty($options[$field_id])) {

            $option = $options[$field_id];

        }

        ?>

        <label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
            <input type="checkbox" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
                   id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" <?php checked($option, true, 1); ?>
                   value="1"/>
            <span class="description"><?php echo esc_html($field_description); ?></span>
        </label>

        <?php
    }

    /**
     * Sandbox our select.
     *
     * @since    2.0.0
     * @param $args
     */
    public function sandbox_add_settings_field_select($args)
    {
        $field_id = $args['label_for'];
        $field_description = $args['description'];
        $select_options = $args['select_options'];
        $selected = null;

        $options = get_option($this->plugin_name . '-settings');
        if (isset($options[$field_id])) {
            $selected = $options[$field_id];
        }
        ?>

        <label for="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>">
            <select name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="">
                <?php foreach ($select_options as $option): ?>
                    <option
                        value="<?php echo $option['value']; ?>" <?php echo $selected == $option['value'] ? 'selected' : ''; ?>>
                        <?php echo $option['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="description"><?php echo esc_html($field_description); ?></span>
        </label>

        <?php
    }

    /**
     * Sandbox our multiple checkboxes
     *
     * @since    2.0.0
     * @param $args
     */
    public function sandbox_add_settings_field_multiple_checkbox($args)
    {
        $field_id = $args['label_for'];
        $field_description = $args['description'];
        $options = get_option($this->plugin_name . '-settings');
        $option = array();
        if ( ! empty($options[$field_id])) {
            $option = $options[$field_id];
        }
        if ($field_id == 'post-types') {
            $args = array(
                'public' => true,
            );
            $post_types = get_post_types($args, 'objects');
            foreach ($post_types as $post_type) {
                if ($post_type->name != 'attachment') {
                    if (in_array($post_type->name, $option)) {
                        $checked = 'checked="checked"';
                    } else {
                        $checked = '';
                    }
                    ?>

                    <fieldset>
                        <label
                            for="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $post_type->name . ']'; ?>">
                            <input type="checkbox"
                                   name="<?php echo $this->plugin_name . '-settings[' . $field_id . '][]'; ?>"
                                   id="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $post_type->name . ']'; ?>"
                                   value="<?php echo esc_attr($post_type->name); ?>" <?php echo $checked; ?> />
                            <span class="description"><?php echo esc_html($post_type->label); ?></span>
                        </label>
                    </fieldset>

                    <?php
                }
            }
        } else {
            $field_args = $args['options'];
            foreach ($field_args as $field_arg_key => $field_arg_value) {
                if (in_array($field_arg_key, $option)) {
                    $checked = 'checked="checked"';
                } else {
                    $checked = '';
                }
                ?>

                <fieldset>
                    <label
                        for="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $field_arg_key . ']'; ?>">
                        <input type="checkbox"
                               name="<?php echo $this->plugin_name . '-settings[' . $field_id . '][]'; ?>"
                               id="<?php echo $this->plugin_name . '-settings[' . $field_id . '][' . $field_arg_key . ']'; ?>"
                               value="<?php echo esc_attr($field_arg_key); ?>" <?php echo $checked; ?> />
                        <span class="description"><?php echo esc_html($field_arg_value); ?></span>
                    </label>
                </fieldset>

                <?php
            }
        }
        ?>

        <p class="description"><?php echo esc_html($field_description); ?></p>

        <?php
    }

    /**
     * Sandbox our inputs with text
     *
     * @since    2.0.0
     * @param $args
     */
    public function sandbox_add_settings_field_input_text($args)
    {
        $field_id = $args['label_for'];
        $field_default = $args['default'];
        $field_description = $args['description'];
        $options = get_option($this->plugin_name . '-settings');
        $option = $field_default;
        if ( ! empty($options[$field_id])) {
            $option = $options[$field_id];
        }
        ?>

        <input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
               id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>"
               value="<?php echo esc_attr($option); ?>" class="regular-text"/>

        <p class="description"><?php echo esc_html($field_description); ?></p>
        <?php
    }

    /**
     * Creates the WordPress Custom Post Type "mynewsdesk_media".
     */
    public function create_cpt()
    {
        register_post_type('mynewsdesk_media',
            array(
                'labels'      => array(
                    'name'          => __('Mynewsdesk'),
                    'singular_name' => __('Mynewsdesk'),
                ),
                'public'      => true,
                'has_archive' => true,
                'supports'    => ['title', 'editor', 'thumbnail', 'page-attributes'],
            )
        );

        // Create tags taxonomy
        register_taxonomy('mynewsdesk_media_tag', 'mynewsdesk_media', array(
                'hierarchical'  => false,
                'label'         => 'Tags',
                'singular_name' => 'Tag',
                'rewrite'       => true,
                'query_var'     => true,
            )
        );

        // Create category taxonomy
        register_taxonomy('mynewsdesk_media_category', 'mynewsdesk_media', array(
                'hierarchical'  => false,
                'label'         => 'Categories',
                'singular_name' => 'Category',
                'rewrite'       => true,
                'query_var'     => true,
            )
        );
    }

    /**
     * Removes all posts in the "mynewsdesk_media" post type.
     */
    public function clear_cpt()
    {
        $all_items = new \WP_Query(['post_type' => 'mynewsdesk_media', 'posts_per_page' => -1]);
        foreach ($all_items->posts as $item) {
            wp_delete_post($item->ID);

            $media = get_children(array(
                'post_parent' => $item->ID,
                'post_type'   => 'attachment',
            ));

            if (empty($media)) {
                continue;
            }

            foreach ($media as $file) {
                wp_delete_attachment($file->ID, true);
            }
        }

        die();
    }

    /**
     * @return mixed
     */
    public function import_mynewsdesk()
    {
        // Set parameters for the API URL.
        $params = array(
            'offset'        => (int)$_POST['data']['offset'],
            'limit'         => 5,
            'order'         => 'created',
            'type_of_media' => isset($_POST['data']['type_of_media']) ? (string)$_POST['data']['type_of_media'] : 'news',
        );

        $result = (new Wpmynewsdesk_Import($this->plugin_name, $this->version))->connect_api($params);

        // Check if response actually contains anything.
        if ($result->items === null) {
            wp_send_json_error([
                'message' => sprintf(__('No data is available for %s.'), $params['type_of_media']),
            ]);

            die();
        }

        // Loop through items and import them to WP.
        foreach ($result->items->item as $item) {
            if ($item !== null)
                (new Wpmynewsdesk_Import($this->plugin_name, $this->version))->item($item);
        }

        $arr = array_merge($params, array(
            'total_count' => $result->items->total_count,
            'items_count' => count($result->items->item),
        ));

//        $items_created = $arr['items_count'] + $arr['offset'];

        // Send success message.
        wp_send_json_success(array(
            'params'  => $arr,
            'message' => sprintf(
                __('Creating %d posts from the %s media type.', $this->plugin_name),
                $result->items->total_count,
                $arr['type_of_media']
            ),
        ));
    }
}

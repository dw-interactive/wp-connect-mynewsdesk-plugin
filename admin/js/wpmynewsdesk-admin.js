jQuery(function ($) {
    'use strict';

    /**
     *
     * @param action  WordPress WP AJAX action
     * @param $button The button to be pressed
     */
    function wp_api_call_button(action, $button) {
        $button.on('click', function (e) {
            e.preventDefault();
            var $this = $(this);

            // Set button to disabled
            $this.prop('disabled', true);

            // Do the API call
            wp_api_call({
                action: action,
                params: function (data) {
                    // Enable the button again
                    $this.prop('disabled', false);
                    // Output result to message container if it exists
                    $button.after("<p>" + data.data.message + "</p>");
                }
            });
        });
    }

    /**
     * WordPress AJAX call
     *
     * @param parameters
     */
    function wp_api_call(parameters) {
        var action = parameters.action;
        var params = parameters.params;
        var callback = parameters.callback;

        // Let's do some AJAX
        var request = $.ajax({
            type: 'post',
            url: wpmynewsdesk_ajax_admin.ajax_url,
            data: {
                nonce: wpmynewsdesk_ajax_admin.nonce,
                action: action,
                data: params
            }
        });

        // Callback
        request.done(function (data) {
            if (callback) {
                callback(data);
            }
        });
    }

    /**
     * This is the button for clearing the plugins transient cache
     * It uses clear_the_cache() in admin/class-wpmynewsdesk-admin.php
     */
    wp_api_call_button('wpmnd_clear_the_cache', $('.js-wpmnd--clear-cache'));

    /**
     * This is the button for importing all Mynewsdesk media through the API
     * It uses import_mynewsdesk() in admin/class-wpmynewsdesk-admin.php
     */

    function run_import(offset, type_of_media) {
        if (offset === undefined) {
            offset = 0;
        }

        wp_api_call({
            action: 'wpmnd_import_mynewsdesk',
            params: {
                offset: offset,
                type_of_media: type_of_media
            },
            callback: function (data) {
                var total = data.data.params.total_count,
                    limit = data.data.params.limit;

                $('.js-wpmnd--import-mynewsdesk-message').html("<p>" + data.data.message + "</p>");

                if (total > offset && total > limit) {
                    offset = offset + limit;
                    if (total > offset) {
                        run_import(offset, type_of_medias);
                    }
                }
            }
        });
    }

    $('.js-wpmnd--import-mynewsdesk').on('click', function (e) {
        // Clear the entire custom post type before importing anything.
        wp_api_call({
            action: 'wpmnd_clear_cpt'
        });

        // Get the checked media types from admin settings page.
        var types = []
        $("input[name='type_of_media[]']:checked").each(function () {
            types.push($(this).val());
        });

        // Loop through and import them.
        $.each(types, function (key, type) {
            run_import(0, type, function (data) {
                $(this).after("<p>" + data.data.message + "</p>");
            });
        });

    });
});

<?php

/**
 * Template for news listing
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/public/partials
 */
?>

<div class="wpmnd--list-item wpmnd--media-type-<?php echo esc_attr($result->type_of_media); ?> id-<?php echo esc_attr($result->id); ?> source-id-<?php echo esc_attr($result->source_id); ?>">
    <header>
        <h2 class="wpmnd--title">
            <a class="wpmnd--link" href="<?php the_permalink(); ?>?id=<?php echo esc_attr($result->id); ?>&type_of_media=<?php echo esc_attr($result->type_of_media); ?>">
                <?php echo esc_html($result->header); ?>
            </a>
        </h2>

        <div class="wpmnd--date">
            <time><?php echo esc_html($result->published_at->format('Y-m-d H:i')); ?></time>
        </div>
    </header>

    <p class="wpmnd--summary"><?php echo esc_html($result->summary); ?></p>
</div>

<?php

/**
 * Template for news single
 *
 * @link       https://dwinteractive.se
 * @since      2.0.0
 *
 * @package    Wpmynewsdesk
 * @subpackage Wpmynewsdesk/public/partials
 */
?>

<div
    itemscope
    itemtype="http://schema.org/NewsArticle"
    class="wpmnd--list-item id-<?php echo esc_attr($result->id); ?> source-id-<?php echo esc_attr($result->source_id); ?>"
>

    <?php do_action('wpmnd_before_single_header'); ?>

    <header>
        <img itemprop="image"
             class="wpmnd--thumbnail"
             src="<?php echo esc_attr($result->image); ?>"
             alt="<?php echo esc_attr($result->image_caption); ?>"
        >

        <div class="wpmnd--date">
            <time><?php echo esc_html($result->published_at->format(get_option('date_format'))); ?></time>
        </div>

        <h2 class="wpmnd--title" itemprop="name"><?php echo esc_html($result->header); ?></h2>
    </header>

    <?php do_action('wpmnd_after_single_header'); ?>

    <div itemprop="articleBody" class="wpmnd--body">
        <?php echo wp_kses_post($result->body); ?>
    </div>

    <?php do_action('wpmnd_after_single_body'); ?>

    <meta itemprop="author" content="<?php echo esc_attr($result->contact_people->contact_person->name); ?>">
    <meta itemprop="wordCount" content="<?php echo esc_attr(str_word_count(strip_tags(strtolower($result->body)))); ?>">
    <meta itemprop="datePublished" content="<?php echo esc_attr($result->published_at->format('c')); ?>">
</div>

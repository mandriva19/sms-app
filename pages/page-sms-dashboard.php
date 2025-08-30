<?php
/* Template Name: SMS Dashboard */
get_header(); ?>

<p class="text-center my-3 px-4 g-2 bg-warning text-dark">
    Lorem, ipsum dolor.
</p>

<div class="sms-dashboard-container">
    <?php
    // Render shortcodes
    echo do_shortcode('[sms_profile_form]');
    echo do_shortcode('[sms_list]');
    echo do_shortcode('[sms_add_form]');
    ?>
</div>

<?php get_footer(); ?>

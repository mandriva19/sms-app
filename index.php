<?php
get_header(); 

?>

<section class="sms_section my-3 px-4">
    <?php
    // Query custom post type "sms"
    $sms_query = new WP_Query([
        'post_type'      => 'sms_sms',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);
    // Create sms posts-loop 
    if ( $sms_query->have_posts() ) :
        while ( $sms_query->have_posts() ) : $sms_query->the_post();
            $color_key   = safe_get_field('sms_color', 'sms_dark');
            // $author_name = safe_get_field('sms_author_name', get_the_author_meta('display_name'));
            $sms_text    = safe_get_field('sms_text', 'Something is wrong with ACF Plugin');
            $author_data = get_post_author_data(); ?> 

        <article class="sms_box animate__animated animate__fadeInDown <?php echo esc_attr($color_key); ?> p-3 text-white mb-4">
            <header class="sms_badges mb-2">
                <span class="sms_badge__location py-1 px-2">
                   <?php echo esc_html($author_data['location']); ?>
                </span>
            </header>
        
            <p class="sms_text mb-2"><?php echo esc_html($sms_text); ?></p>

            <footer class="sms_meta">
                <small class="sms_author">
                    — <?php echo esc_html($author_data['display_name']); ?>
                    <time class="sms_date" datetime="<?php echo get_the_date('c'); ?>">
                        @<?php echo esc_html(get_the_date('H:i M j, Y')); ?>
                    </time>
                </small>
            </footer>
        </article>

            <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No SMS found.</p>';
    endif;
    ?>

</section>

<?php
get_footer();
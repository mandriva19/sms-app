<?php get_header(); ?>

<?php get_header(); 

$author_data = get_post_author_data(); ?>

<p class="text-xl bg-warning text-dark">
    <?php echo esc_html($author_data['author_bio']); ?> <br>
    <?php echo esc_html($author_data['first_name']); ?> 
    <?php echo esc_html($author_data['last_name']); ?> <br>
    <?php echo esc_html($author_data['location']); ?> <br>
</p>

<?php 
    $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

    $sms_query_authors = new WP_Query([
        'author' => get_queried_object_id(),
        'post_type'      => 'sms_sms',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'paged'          => $paged,
    ]);

     if ( $sms_query_authors->have_posts() ) :
        while ( $sms_query_authors->have_posts() ) : $sms_query_authors->the_post();
            $color_key   = safe_get_field('sms_color', 'sms_dark');
            // $author_name = safe_get_field('sms_author_name', get_the_author_meta('display_name'));
            $sms_text    = safe_get_field('sms_text', 'Something is wrong with ACF Plugin');
            $author_data = get_post_author_data(); ?> 

        <article class="sms_box mx-5 <?php echo esc_attr($color_key); ?> p-3 text-white mb-4">
            <header class="sms_badges mb-2">
                <span class="sms_badge__location py-1 px-2">
                   <a class="a_sms_location" href="<?php echo esc_url( get_author_posts_url( $author_data['location'] ) ); ?>">
                    <?php echo esc_html($author_data['location']); ?>
                   </a>
                </span>
                <span class="sms_badge__subject py-1 px-2 border border-dark">
                    ⚠️
                </span>
            </header>
        
            <p class="sms_text mb-1">
                <?php echo esc_html($sms_text); ?>
            </p>

            <footer class="sms_meta">
                <small class="sms_author">
                    — <a href="<?php echo esc_url( get_author_posts_url( $author_data['author_id'] ) ); ?>">
                        <?php echo esc_html($author_data['display_name']); ?>
                      </a>
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

<?php get_footer(); ?>
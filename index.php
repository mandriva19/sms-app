<?php get_header(); ?>
<!-- unified-modal -->
<?php get_template_part( './app/php/modal' ); ?>

<!-- temporary logo -->
<!-- <div class="logo-flex mt-3">
    <a href="#">
        <img class="logo-tmp " 
        src="http://localhost/web/wp-content/uploads/2025/08/logo-1.png" 
        alt="">
    </a>
</div> -->
<!-- Main sms section -->
<section class="sms_section my-3 px-4 g-2">
    <!-- Live circle animation -->
    <div class="d-flex align-items-center">
        <div class="beating-circle animate__animated animate__heartBeat animate__infinite infinite mb-4"></div>
        <!-- <span class="text-danger py-2 px-2 mb-4">Live განახლება</span> -->
    </div>
    <?php
    // filter custom post type "sms"
    $sms_query = new WP_Query([
        'post_type'      => 'sms_sms',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);
    $counter = 0; //counts the loop itteration

    // Create sms posts-loop 
    if ( $sms_query->have_posts() ) :
        while ( $sms_query->have_posts() ) : $sms_query->the_post();
            $counter++;
            $color_key   = safe_get_field('sms_color', 'sms_dark');
            // $author_name = safe_get_field('sms_author_name', get_the_author_meta('display_name'));
            $sms_text    = safe_get_field('sms_text', 'Something is wrong with ACF Plugin');
            $author_data = get_global_user_meta(); ?> 

        <article class="sms_box animate__animated animate__fadeInDown <?php echo esc_attr($color_key); ?> p-3 text-white mb-4">
            <header class="sms_badges mb-2">
                <span class="sms_badge__location py-1 px-2">
                   <a class="a_sms_location" href="<?php echo esc_url( get_author_posts_url( $author_data['location'] ) ); ?>">
                    <?php echo esc_html( $author_data['location'] ); ?>
                   </a>
                </span>
                <!-- <span class="sms_badge__subject py-1 px-2 border border-dark">
                    CRASH
                </span> -->
            </header>
        
            <p class="sms_text mb-1">
                <?php echo esc_html($sms_text); ?>
            </p>

            <footer class="sms_meta">
                <small class="sms_author">
                    — <a 
                    class="user-link" 
                    data-user-id="<?= $author_data['id'] ?>" 
                    data-username="<?= $author_data['username'] ?>" 
                    href="<?php echo esc_url( get_author_posts_url( $author_data['id'] ) ); ?>">
                        <?php echo esc_html($author_data['display_name']); ?>
                      </a>
                    <time class="sms_date" datetime="<?php echo get_the_date('c'); ?>">
                        @<?php echo esc_html(get_the_date('H:i M j, Y')); ?>
                    </time>
                </small>
            </footer>
        </article>
        <?php 
        // Supporters area -> appears after X sms 
            $custom_box_positions = [3, 10];
            if (in_array($counter, $custom_box_positions)) {
                echo '<div class="custom_content_box mb-4">
                       <h1>1 მხარდამჭერის სივრცე</h1>
                </div>';
            }
            $custom_box_positions_2 = [6, 13];
            if (in_array($counter, $custom_box_positions_2)) {
                echo '<div class="custom_content_box mb-4">
                    <h1>2 მხარდამჭერის სივრცე</h1>
                </div>';
            }
            ?>    
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
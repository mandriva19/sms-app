<?php
// Ajax handler for logged-in and guest users
add_action('wp_ajax_get_latest_sms', 'get_latest_sms');
add_action('wp_ajax_nopriv_get_latest_sms', 'get_latest_sms');

function get_latest_sms() {
    $sms_query = new WP_Query([
        'post_type'      => 'sms_sms',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'post_status'    => 'publish',
    ]);
         // get sms data if posts exists in sms query
    if ( $sms_query->have_posts() ) {
        $sms_query->the_post();
        // get custom authordata function 
        $author_data = get_post_author_data();
        $response = [
            'id'         => get_the_ID(),
            'color'   => safe_get_field('sms_color', 'sms_danger'),
            'author' => $author_data['display_name'],
            'location' => $author_data['location'],
            'text'    => safe_get_field('sms_text', 'Something is wrong with acf plugin'),
            'date'   => get_the_date('H:i M j, Y'),
        ];

        wp_send_json_success($response);
    } else {
        wp_send_json_error('No SMS found');
    }

    wp_die();
}

// Register AJAX action for logged-in and guests
add_action('wp_ajax_get_user_modal_data', 'get_user_modal_data');
add_action('wp_ajax_nopriv_get_user_modal_data', 'get_user_modal_data');

function get_user_modal_data() {
    if ( ! isset($_POST['user_id']) ) {
        wp_send_json_error(['message' => 'Missing user ID']);
    }

    $user_id = intval($_POST['user_id']);
    $user = get_userdata($user_id);

    if (!$user) {
        wp_send_json_error(['message' => 'User not found']);
    } 

    $author_data = get_post_author_data($user_id);

    $user_sms_query = new WP_Query([
    'author'      => $author_data['id'],
    'post_type'   => 'sms_sms', // 
    'post_status' => 'publish',
    'fields'      => 'ids',     // 
]);

$sms_count = $user_sms_query->found_posts;

    wp_send_json_success([
        'id'       => $author_data['id'],
        'name'     => $author_data['real_name'], // 👈 first+last with your fallback logic
        'display'  => $author_data['display_name'],
        'location' => $author_data['location'],
        'bio'      => $author_data['bio'],
        'posts' => $sms_count,
        'avatar'   => get_avatar_url($author_data['id'], ['size' => 200]),
        'profile'  => get_author_posts_url($author_data['id']),
    ]);
}

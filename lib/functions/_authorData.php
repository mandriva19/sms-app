<?php

function get_post_author_data() {
    $post_author_id = get_the_author_meta('ID');
    // Get author location
    $author_location = get_user_meta($post_author_id, 'user_location', true);
    // Get real name
    $first_name = get_user_meta($post_author_id, 'first_name', true);
    $last_name = get_user_meta($post_author_id, 'last_name', true);
    // build real name to output as realname or fallback to display name or log-in name
    if (!empty($first_name) && !empty($last_name)) {
        $author_real_name = $first_name . ' ' . $last_name;
    } elseif (!empty($first_name)) {
        $author_real_name = $first_name;
    } elseif (!empty($last_name)) {
        $author_real_name = $last_name;
    } else {
        $author_real_name = get_the_author_meta('display_name') ?: get_the_author_meta('user_login');
    }
    // User-option --> Determine to show sms author as(real name) or 'anonymous'

    $post_as  = safe_get_field('post_as', 'real_name');

    if ($post_as === 'anonymous') {
        $display_name = 'anonymous' . $post_author_id;
    } else {
        $display_name = $author_real_name;
    }
    // return user data
    return [
        'author_id' => $post_author_id,
        'location' => $author_location ?: 'empty',
        'real_name' => $author_real_name,
        'display_name' => $display_name,
        'first_name' => $first_name,
        'last_name' => $last_name
    ];
}
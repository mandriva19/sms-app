<?php
// unified function to get author meta data
function get_global_user_meta($user_id = null) {
    $post_author_id = $user_id ? intval($user_id) : get_the_author_meta('ID');

    if (!$post_author_id) {
        return [];
    }
    // Get author meta data
    $author_location = get_user_meta($post_author_id, 'user_location', true);
    $first_name = get_user_meta($post_author_id, 'first_name', true);
    $last_name = get_user_meta($post_author_id, 'last_name', true);
    $author_bio = get_user_meta($post_author_id, 'author_bio', true);
    $author_username = get_the_author_meta('user_nicename'); 
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
    // set display name based on user option (upon sms sub mission)
        if ($post_as === 'anonymous') {
        $display_name = 'anonymous' . $post_author_id;
        } else {
        $display_name = $author_real_name;
        }
    // return user data
    return [
    'id' => $post_author_id,
    'location' => $author_location ?: 'empty',
    'real_name' => $author_real_name,
    'display_name' => $display_name,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'bio' => $author_bio,
    'username' => $author_username
    ];
}
